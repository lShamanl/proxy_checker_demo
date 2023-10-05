<?php

declare(strict_types=1);

namespace App\Tests\Integration\ProxyChecker\Domain\Proxy\Service;

use App\ProxyChecker\Domain\Proxy\Service\ProxyNextIdService;
use App\ProxyChecker\Domain\Proxy\ValueObject\ProxyId;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\ProxyChecker\Domain\Proxy\Service\ProxyNextIdService */
class ProxyNextIdServiceTest extends IntegrationTestCase
{
    protected static ProxyNextIdService $proxyNextIdService;

    public function setUp(): void
    {
        parent::setUp();
        self::$proxyNextIdService = self::$containerTool->get(ProxyNextIdService::class);
    }

    public function testAllocateId(): void
    {
        $id = self::$proxyNextIdService->allocateId();

        self::assertInstanceOf(ProxyId::class, $id);
        self::assertSame(
            (int) $id->getValue() + 1,
            (int) self::$proxyNextIdService->allocateId()->getValue()
        );
    }
}
