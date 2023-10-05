<?php

declare(strict_types=1);

namespace App\ProxyChecker\Domain\Proxy\ValueObject;

use Webmozart\Assert\Assert;

class ProxyId
{
    public function __construct(private readonly string $value)
    {
        Assert::notEmpty($value);
        Assert::numeric($value);
        Assert::greaterThan($value, 0);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
