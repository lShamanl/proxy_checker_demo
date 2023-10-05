<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Delete;

use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;

final readonly class Handler
{
    public function __construct(
        private Flusher $flusher,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(Command $command): Result
    {
        $user = $this->userRepository->findById($command->id);
        if (null === $user) {
            return Result::userNotExists();
        }

        $this->userRepository->remove($user);
        $this->flusher->flush($user);

        return Result::success($user);
    }
}
