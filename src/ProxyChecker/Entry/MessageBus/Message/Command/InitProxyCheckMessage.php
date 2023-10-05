<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\MessageBus\Message\Command;

readonly class InitProxyCheckMessage
{
    public function __construct(
        public string $checkListId,
        public string $proxy,
    ) {
    }
}
