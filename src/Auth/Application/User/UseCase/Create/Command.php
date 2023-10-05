<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Create;

final readonly class Command
{
    public function __construct(
        public string $plainPassword,
        public string $name,
        public string $email,
        public string $role,
    ) {
    }
}
