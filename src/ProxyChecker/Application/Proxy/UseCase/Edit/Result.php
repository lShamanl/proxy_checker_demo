<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\Proxy\UseCase\Edit;

use App\ProxyChecker\Domain\Proxy\Proxy;

class Result
{
    public function __construct(
        public readonly ResultCase $case,
        public ?Proxy $proxy = null,
        public array $context = [],
    ) {
    }

    public static function success(
        Proxy $proxy,
        array $context = [],
    ): self {
        return new self(
            case: ResultCase::Success,
            proxy: $proxy,
            context: $context
        );
    }

    public function isSuccess(): bool
    {
        return $this->case->isEqual(ResultCase::Success);
    }

    public static function proxyNotExists(array $context = []): self
    {
        return new self(case: ResultCase::ProxyNotExists, context: $context);
    }

    public function isProxyNotExists(): bool
    {
        return $this->case->isEqual(ResultCase::ProxyNotExists);
    }
}
