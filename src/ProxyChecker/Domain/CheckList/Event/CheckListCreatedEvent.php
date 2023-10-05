<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\CheckList\Event;

readonly class CheckListCreatedEvent
{
    public function __construct(
        public string $id,
    ) {
    }
}
