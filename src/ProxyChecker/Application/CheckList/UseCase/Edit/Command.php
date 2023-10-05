<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Edit;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use DateTimeImmutable;

readonly class Command
{
    public function __construct(
        public CheckListId $id,
        public ?DateTimeImmutable $endAt,
        public ?string $payload,
        public ?int $allIteration,
        public ?int $successIteration,
    ) {
    }
}
