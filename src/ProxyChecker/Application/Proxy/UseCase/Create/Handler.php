<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Create;

use App\Common\Service\Core\Flusher;
use App\ProxyChecker\Domain\CheckList\CheckListRepository;
use App\ProxyChecker\Domain\Proxy\Proxy;
use App\ProxyChecker\Domain\Proxy\ProxyRepository;
use App\ProxyChecker\Domain\Proxy\Service\ProxyNextIdService;
use DateTimeImmutable;

readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private ProxyRepository $proxyRepository,
        private ProxyNextIdService $proxyNextIdService,
        private CheckListRepository $checkListRepository,
    ) {
    }

    public function handle(Command $command): Result
    {
        $checkList = $this->checkListRepository->findById($command->checkListId);
        if (null === $checkList) {
            return Result::checkListNotExists();
        }
        $now = new DateTimeImmutable();
        [$ipProxy, $port] = explode(':', $command->proxy);
        dd($ipProxy, $port, $command->proxy);
        $proxy = new Proxy(
            id: $this->proxyNextIdService->allocateId(),
            createdAt: $now,
            updatedAt: $now,
            ipProxy: $command->ipProxy,
            ipReal: $command->ipReal,
            port: $command->port,
            proxyType: $command->proxyType,
            proxyStatus: $command->proxyStatus,
            country: $command->country,
            region: $command->region,
            city: $command->city,
            timeout: $command->timeout,
            checkList: $checkList
        );

        $this->proxyRepository->add($proxy);
        $this->flusher->flush($proxy);

        return Result::success(
            proxy: $proxy,
            context: [
                'id' => $proxy->getId()->getValue(),
            ]
        );
    }
}
