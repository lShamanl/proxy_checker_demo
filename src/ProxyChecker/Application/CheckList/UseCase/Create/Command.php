<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Create;

use DateTimeImmutable;

readonly class Command
{
    public function __construct(
        public ?DateTimeImmutable $endAt,
        public string $payload,
        public ?int $allIteration,
        public ?int $successIteration,
    ) {
    }
}
