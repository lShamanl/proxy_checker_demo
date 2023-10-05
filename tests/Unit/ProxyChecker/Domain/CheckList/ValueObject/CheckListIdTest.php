<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProxyChecker\Domain\CheckList\ValueObject;

use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\Tests\Unit\UnitTestCase;

/** @covers \App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId */
class CheckListIdTest extends UnitTestCase
{
    public function testToString(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new CheckListId($value))->__toString());
    }

    public function testGetValue(): void
    {
        $value = (string) random_int(1, 999);
        self::assertSame($value, (new CheckListId($value))->getValue());
    }
}
