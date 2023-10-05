<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Remove;

enum ResultCase
{
    case Success;
    case ProxyNotExists;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
