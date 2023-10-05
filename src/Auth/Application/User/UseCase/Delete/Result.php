<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Delete;

use App\Auth\Domain\User\User;

class Result
{
    public function __construct(
        public readonly ResultCase $case,
        public ?User $user = null,
    ) {
    }

    public static function success(User $user): self
    {
        return new Result(case: ResultCase::Success, user: $user);
    }

    public static function userNotExists(): self
    {
        return new Result(case: ResultCase::UserNotExists);
    }

    public function isSuccess(): bool
    {
        return $this->case->isEqual(ResultCase::Success);
    }

    public function isUserNotExists(): bool
    {
        return $this->case->isEqual(ResultCase::UserNotExists);
    }
}
