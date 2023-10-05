<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Create;

enum ResultCase
{
    case Success;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
