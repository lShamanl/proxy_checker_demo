<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Remove;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;

readonly class Command
{
    public function __construct(public CheckListId $id)
    {
    }
}
