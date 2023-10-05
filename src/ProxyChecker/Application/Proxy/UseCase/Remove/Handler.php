<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Remove;

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

        $this->proxyRepository->remove($proxy);
        $this->flusher->flush($proxy);

        return Result::success(
            proxy: $proxy,
            context: [
                'id' => $command->id->getValue(),
            ]
        );
    }
}
