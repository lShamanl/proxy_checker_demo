<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Create;

use App\Common\Service\Core\Flusher;
use App\ProxyChecker\Domain\CheckList\CheckList;
use App\ProxyChecker\Domain\CheckList\CheckListRepository;
use App\ProxyChecker\Domain\CheckList\Service\CheckListNextIdService;
use DateTimeImmutable;

readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private CheckListRepository $checkListRepository,
        private CheckListNextIdService $checkListNextIdService,
    ) {
    }

    public function handle(Command $command): Result
    {
        $now = new DateTimeImmutable();
        $checkList = new CheckList(
            id: $this->checkListNextIdService->allocateId(),
            createdAt: $now,
            updatedAt: $now,
            endAt: null,
            payload: $command->payload,
            allIteration: count(explode(PHP_EOL, $command->payload)),
            successIteration: 0,
        );

        $this->checkListRepository->add($checkList);
        $this->flusher->flush($checkList);

        return Result::success(
            checkList: $checkList,
            context: [
                'id' => $checkList->getId()->getValue(),
            ]
        );
    }
}
