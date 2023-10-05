<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\Event;

readonly class UserCreatedEvent
{
    public function __construct(
        public string $id,
    ) {
    }
}
