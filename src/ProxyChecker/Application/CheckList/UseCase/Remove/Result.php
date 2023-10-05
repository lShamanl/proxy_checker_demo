<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Remove;

use App\ProxyChecker\Domain\CheckList\CheckList;

class Result
{
    public function __construct(
        public readonly ResultCase $case,
        public ?CheckList $checkList = null,
        public array $context = [],
    ) {
    }

    public static function success(
        CheckList $checkList,
        array $context = [],
    ): self {
        return new self(
            case: ResultCase::Success,
            checkList: $checkList,
            context: $context
        );
    }

    public function isSuccess(): bool
    {
        return $this->case->isEqual(ResultCase::Success);
    }

    public static function checkListNotExists(array $context = []): self
    {
        return new self(case: ResultCase::CheckListNotExists, context: $context);
    }

    public function isCheckListNotExists(): bool
    {
        return $this->case->isEqual(ResultCase::CheckListNotExists);
    }
}
