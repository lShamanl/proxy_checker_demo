<?php

declare(strict_types=1);

namespace App\Tests\Integration\Auth\Application\User\UseCase\Delete;

use App\Auth\Application\User\UseCase\Delete\Command;
use App\Auth\Application\User\UseCase\Delete\Handler;
use App\Auth\Application\User\UseCase\Delete\ResultCase;
use App\Auth\Domain\User\UserRepository;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Tests\Integration\IntegrationTestCase;

/** @covers \App\Auth\Application\User\UseCase\Delete\Handler */
class HandlerTest extends IntegrationTestCase
{
    protected static Handler $handler;
    protected static UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        self::$handler = self::$containerTool->get(Handler::class);
        self::$userRepository = self::$containerTool->get(UserRepository::class);
    }

    protected static function withFixtures(): array
    {
        return [
            Fixture::class,
        ];
    }

    public function testHandleWhenSuccess(): void
    {
        $result = self::$handler->handle(
            $command = new Command(
                id: new UserId(Fixture::ID),
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::Success)
        );
        self::assertNull(
            self::$userRepository->findById($command->id)
        );
    }

    public function testHandleWhenUserNotExists(): void
    {
        $result = self::$handler->handle(
            new Command(
                id: new UserId('100000'),
            )
        );
        self::assertTrue(
            $result->case->isEqual(ResultCase::UserNotExists)
        );
        self::assertNull($result->user);
    }
}
