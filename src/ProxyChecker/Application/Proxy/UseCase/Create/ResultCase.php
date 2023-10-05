<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Create;

enum ResultCase
{
    case Success;
    case CheckListNotExists;
    case CanNotProcessing;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
