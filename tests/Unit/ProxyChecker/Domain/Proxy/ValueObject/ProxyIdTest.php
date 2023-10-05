<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProxyChecker\Domain\Proxy\ValueObject;

use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use App\Tests\Unit\UnitTestCase;

/** @covers \App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId */
class ProxyIdTest extends UnitTestCase
{
    public function testToString(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new ProxyId($value))->__toString());
    }

    public function testGetValue(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new ProxyId($value))->getValue());
    }
}
