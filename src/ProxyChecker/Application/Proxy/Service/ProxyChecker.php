<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\Service;

use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;
use App\ProxyChecker\Domain\Proxy\Proxy;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Throwable;

readonly class ProxyChecker
{
    public function __construct(
    ) {
    }

    public function check(Proxy $proxy): void
    {
        $request = $this->tryRequest($proxy, 'http') ?? $this->tryRequest($proxy, 'socks5');
        if (null === $request) {
            return;
        }
        $payload = $request->getBody()->getContents();
        $json = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        $proxy->setRealIp($json['query'] ?? '');
    }

    public function tryRequest(Proxy $proxy, string $protocol): ?ResponseInterface
    {
        try {
            $start = microtime(true);

            $client = new Client([
                'timeout' => 100,
                'proxy' => [
                    sprintf(
                        '%s://%s:%s',
                        $protocol,
                        $proxy->getIpProxy(),
                        $proxy->getPort(),
                    ),
                ],
            ]);

            $response = $client->get('http://ip-api.com/json');
            $end = microtime(true);

            $duration = ($end - $start) * 1000; // Преобразовываем в миллисекунды


//            dd($result, $duration);

            $proxy->setTimeout((int) $duration);
            $proxyType = ProxyType::tryFrom($protocol);
            if (null !== $proxyType) {
                $proxy->setProxyType($proxyType);
            }


            return $response;
        } catch (Throwable $throwable) {
            return null;
        }
    }
}
