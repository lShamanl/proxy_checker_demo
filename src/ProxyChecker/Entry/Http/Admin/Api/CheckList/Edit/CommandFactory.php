<?php

declare(strict_types=1);

namespace App\ProxyChecker\Entry\Http\Admin\Api\CheckList\Edit;

use App\ProxyChecker\Application\CheckList\UseCase\Edit\Command;
use App\ProxyChecker\Domain\CheckList\ValueObject\CheckListId;
use DateTimeImmutable;

class CommandFactory
{
    public function create(InputContract $inputContract): Command
    {
        if (!isset(
            $inputContract->id,
            $inputContract->payload,
        )) {
            throw new \Exception('Check NotNull asserts in ' . InputContract::class . ' for required properties');
        }

        return new Command(
            id: new CheckListId($inputContract->id),
            endAt: null !== $inputContract->endAt ? new DateTimeImmutable($inputContract->endAt) : null,
            payload: $inputContract->payload,
            allIteration: null !== $inputContract->allIteration ? $inputContract->allIteration : null,
            successIteration: null !== $inputContract->successIteration ? $inputContract->successIteration : null,
        );
    }
}
