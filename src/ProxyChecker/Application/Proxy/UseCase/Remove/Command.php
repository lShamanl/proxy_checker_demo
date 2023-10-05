<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Remove;

use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;

readonly class Command
{
    public function __construct(public ProxyId $id)
    {
    }
}
