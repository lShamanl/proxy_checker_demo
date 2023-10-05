<?php

declare(strict_types=1);

namespace App\Tests\Integration\ProxyChecker\Domain\CheckList\Service;

use App\ProxyChecker\Domain\CheckList\Service\CheckListNextIdService;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\ProxyChecker\Domain\CheckList\Service\CheckListNextIdService */
class CheckListNextIdServiceTest extends IntegrationTestCase
{
    protected static CheckListNextIdService $checkListNextIdService;

    public function setUp(): void
    {
        parent::setUp();
        self::$checkListNextIdService = self::$containerTool->get(CheckListNextIdService::class);
    }

    public function testAllocateId(): void
    {
        $id = self::$checkListNextIdService->allocateId();

        self::assertInstanceOf(CheckListId::class, $id);
        self::assertSame(
            (int) $id->getValue() + 1,
            (int) self::$checkListNextIdService->allocateId()->getValue()
        );
    }
}
