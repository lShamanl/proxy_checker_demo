<?php

declare(strict_types=1);

namespace App\Common\EventSubscriber\Exception;

use App\Common\Exception\Domain\DomainException;
use App\Common\Service\Metrics\AdapterInterface;
use Exception;
use IWD\Symfony\PresentationBundle\Exception\DeserializePayloadToInputContractException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use IWD\Symfony\PresentationBundle\Dto\Output\ApiFormatter;
use IWD\Symfony\PresentationBundle\Exception\PresentationBundleException;
use Throwable;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'logException', priority: 2)]
#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onFormatterException', priority: 1)]
class HttpExceptionSubscriber
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger,
        private readonly AdapterInterface $metrics,
        #[Autowire('%env(APP_ENV)%')]
        private readonly string $env,
        #[Autowire('%env(LOCAL_TEST)%')]
        private readonly bool $debug,
    ) {
    }

    public function logException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->metrics->createCounter(
            name: 'error:http',
            help: 'error in http'
        )->inc();
        try {
            throw $exception;
        } catch (DeserializePayloadToInputContractException $exception) {
            $this->logger->warning(
                sprintf(
                    'Failed deserialize request to InputContract. Payload: %s, Error: %s',
                    json_encode($exception->getPayload(), JSON_THROW_ON_ERROR),
                    $exception->getPrevious()?->getMessage()
                )
            );
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage());
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    public function onFormatterException(ExceptionEvent $event): void
    {
        $format = (string) $event->getRequest()->attributes->get('_format', 'json');
        $event->allowCustomResponseCode();

        $exception = $event->getThrowable();
        if ($exception instanceof AccessDeniedException) {
            /** @var \Symfony\Component\HttpFoundation\Request|null $request */
            $request = $exception->getSubject();
            if (null !== $request && Request::METHOD_GET === $request->getMethod()) {
                return;
            }
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize(
                    ApiFormatter::prepare(
                        null,
                        Response::HTTP_UNAUTHORIZED,
                        $exception->getMessage(),
                    ),
                    $format
                )
            );
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
            $response->headers->add(['Content-Type' => 'application/' . $format]);
            $event->setResponse($response);

            return;
        }

        try {
            $previous = $exception->getPrevious();
            if (!empty($previous) && is_subclass_of($previous, DomainException::class)) {
                throw $previous;
            }

            throw $exception;
        } catch (DomainException|PresentationBundleException $exception) {
            $response = new Response();
            $response->setContent(
                $this->serializer->serialize(
                    $this->toApiFormat($exception, Response::HTTP_UNPROCESSABLE_ENTITY),
                    $format
                )
            );
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->headers->add(['Content-Type' => 'application/' . $format]);
            $event->setResponse($response);
        } catch (\Doctrine\DBAL\Exception $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_BAD_REQUEST,
                            ['Bad Request']
                        ),
                        $format
                    )
                );
                $response->headers->add(['Content-Type' => 'application/' . $format]);
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                $event->setResponse($response);
            }
        } catch (Throwable $exception) {
            if ($this->isDev() || ($this->isTest() && $this->debug)) {
                return;
            }
            if ($this->isTest() || $this->isProd()) {
                $response = new Response();
                $response->setContent(
                    $this->serializer->serialize(
                        ApiFormatter::prepare(
                            null,
                            Response::HTTP_INTERNAL_SERVER_ERROR,
                            'Internal Server Error'
                        ),
                        $format
                    )
                );
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $response->headers->add(['Content-Type' => 'application/' . $format]);
                $event->setResponse($response);
            }
        }
    }

    protected function isDev(): bool
    {
        return 'dev' === $this->env;
    }

    protected function isTest(): bool
    {
        return 'test' === $this->env;
    }

    protected function isProd(): bool
    {
        return 'prod' === $this->env;
    }

    protected function toApiFormat(Exception $exception, ?int $code = null): array
    {
        $errors = $this->isValidJson($exception->getMessage())
            ? json_decode($exception->getMessage(), true, 512, JSON_THROW_ON_ERROR)
            : [$exception->getMessage()]
        ;

        return ApiFormatter::prepare(
            [],
            $code ?? $exception->getCode(),
            $errors
        );
    }

    /**
     * @param string $string
     */
    protected function isValidJson($string): bool
    {
        return is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string)));
    }
}
