<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Edit;

enum ResultCase
{
    case Success;
    case CheckListNotExists;

    public function isEqual(self $enum): bool
    {
        return $this->name === $enum->name;
    }
}
