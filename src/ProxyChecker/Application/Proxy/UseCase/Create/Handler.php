<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Create;

use App\Common\Service\Core\Flusher;
use App\ProxyChecker\Application\Proxy\Service\ProxyChecker;
use App\ProxyChecker\Domain\CheckList\CheckListRepository;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use App\ProxyChecker\Domain\Proxy\Proxy;
use App\ProxyChecker\Domain\Proxy\ProxyRepository;
use App\ProxyChecker\Domain\Proxy\Service\ProxyNextIdService;
use DateTimeImmutable;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;

readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private ProxyRepository $proxyRepository,
        private ProxyNextIdService $proxyNextIdService,
        private CheckListRepository $checkListRepository,
        private ClientInterface $client,
        private ProxyChecker $proxyChecker,
    ) {
    }

    public function handle(Command $command): Result
    {
        $checkList = $this->checkListRepository->findById($command->checkListId);
        if (null === $checkList) {
            return Result::checkListNotExists();
        }
        $now = new DateTimeImmutable();
        if (str_contains($command->proxy, ':')) {
            [$ipProxy, $port] = explode(':', $command->proxy);
        } else {
            // В случае, если разделитель ":" не найден, установите значения по умолчанию
            $ipProxy = $command->proxy;
            $port = 80; // или другое значение по умолчанию
        }

        $response = $this->client->request(
            method: 'GET',
            uri: 'http://proxycheck.io/v2/' . $ipProxy,
            options: [
                RequestOptions::FORM_PARAMS => [
                    'ips',
                ],
                RequestOptions::QUERY => [
                    'key' => '417g66-52d6x5-51312c-65c750',
                    'vpn' => 1,
                    'asn' => 1,
                    'risk' => 1,
                    'port' => 1,
                    'seen' => 1,
                    'days' => 7,
                    'tag' => 'msg',
                ],
            ],
        );
        $payload = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        if ('error' === $payload['status']) {
            $proxy = new Proxy(
                id: $this->proxyNextIdService->allocateId(),
                createdAt: $now,
                updatedAt: $now,
                ipProxy: $ipProxy,
                ipReal: '',
                port: (int) $port,
                proxyType: ProxyType::Other,
                proxyStatus: ProxyStatus::NotWork,
                country: null,
                region: null,
                city: null,
                timeout: null,
                checkList: $checkList
            );
        }
        if ('ok' === $payload['status']) {
            $proxyPayload = $payload[$ipProxy];

            $proxy = new Proxy(
                id: $this->proxyNextIdService->allocateId(),
                createdAt: $now,
                updatedAt: $now,
                ipProxy: $ipProxy,
                ipReal: '',
                port: (int) $port,
                proxyType: ProxyType::Other,
                proxyStatus: ProxyStatus::Work,
                country: $proxyPayload['country'],
                region: $proxyPayload['region'],
                city: $proxyPayload['city'],
                timeout: null,
                checkList: $checkList
            );

            $this->proxyChecker->check($proxy);
            $checkList->incrementSuccessIteration();
        }
        if (isset($proxy)) {
            /** @var Proxy $proxy */
            $this->proxyRepository->add($proxy);
            $this->flusher->flush($proxy);

            return Result::success(
                proxy: $proxy,
                context: [
                    'id' => $proxy->getId()->getValue(),
                ]
            );
        }

        return Result::canNotProcessing(
            proxy: $command->proxy,
        );
    }
}
