<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Delete;

enum ResultCase
{
    case Success;
    case UserNotExists;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
