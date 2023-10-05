<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Edit;

use App\Common\Service\Core\Flusher;
use App\ProxyChecker\Domain\Proxy\ProxyRepository;

readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private ProxyRepository $proxyRepository,
    ) {
    }

    public function handle(Command $command): Result
    {
        $proxy = $this->proxyRepository->findById($command->id);
        if (null === $proxy) {
            return Result::proxyNotExists();
        }

        $ipProxy = $command->ipProxy ?? $proxy->getIpProxy();
        $ipReal = $command->ipReal ?? $proxy->getIpReal();
        $port = $command->port ?? $proxy->getPort();
        $proxyType = $command->proxyType ?? $proxy->getProxyType();
        $proxyStatus = $command->proxyStatus ?? $proxy->getProxyStatus();
        $country = $command->country ?? $proxy->getCountry();
        $region = $command->region ?? $proxy->getRegion();
        $city = $command->city ?? $proxy->getCity();
        $timeout = $command->timeout ?? $proxy->getTimeout();

        $proxy->edit(
            ipProxy: $ipProxy,
            ipReal: $ipReal,
            port: $port,
            proxyType: $proxyType,
            proxyStatus: $proxyStatus,
            country: $country,
            region: $region,
            city: $city,
            timeout: $timeout
        );

        $this->flusher->flush($proxy);

        return Result::success(
            proxy: $proxy,
            context: [
                'id' => $command->id->getValue(),
            ]
        );
    }
}
