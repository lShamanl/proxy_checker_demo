<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Create;

readonly class Command
{
    public function __construct(
        public string $payload,
    ) {
    }
}
