<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Create;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;

class Command
{
    public function __construct(
        public CheckListId $checkListId,
        public string $proxy,
    ) {
    }
}
