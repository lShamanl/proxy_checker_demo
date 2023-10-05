<?php

declare(strict_types=1);

namespace App\Auth\Domain\User\ValueObject;

use Webmozart\Assert\Assert;

readonly class UserId
{
    public function __construct(
        private string $value
    ) {
        Assert::notEmpty($value);
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
