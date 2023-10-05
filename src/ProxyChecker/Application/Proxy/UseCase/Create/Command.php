<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Create;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyStatus;
use App\ProxyChecker\Domain\Proxy\Enum\ProxyType;

readonly class Command
{
    public function __construct(
        public string $ipProxy,
        public string $ipReal,
        public int $port,
        public ProxyType $proxyType,
        public ProxyStatus $proxyStatus,
        public ?string $country,
        public ?string $region,
        public ?string $city,
        public ?int $timeout,
        public CheckListId $checkListId,
    ) {
    }
}
