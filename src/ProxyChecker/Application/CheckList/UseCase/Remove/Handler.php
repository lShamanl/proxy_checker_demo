<?php

declare(strict_types=1);

namespace App\ProxyChecker\Application\CheckList\UseCase\Remove;

use App\Common\Service\Core\Flusher;
use App\ProxyChecker\Domain\CheckList\CheckListRepository;

readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private CheckListRepository $checkListRepository,
    ) {
    }

    public function handle(Command $command): Result
    {
        $checkList = $this->checkListRepository->findById($command->id);
        if (null === $checkList) {
            return Result::checkListNotExists();
        }

        $this->checkListRepository->remove($checkList);
        $this->flusher->flush($checkList);

        return Result::success(
            checkList: $checkList,
            context: [
                'id' => $command->id->getValue(),
            ]
        );
    }
}
