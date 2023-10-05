<?php

declare(strict_types=1);

namespace App\Auth\Application\User\UseCase\Create;

use App\Auth\Domain\User\User;
use App\Auth\Domain\User\UserRepository;
use App\Common\Service\Core\Flusher;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class Handler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): Result
    {
        if ($this->userRepository->hasByEmail($command->email)) {
            return Result::emailIsBusy();
        }

        $now = new DateTimeImmutable();
        $user = User::create(
            id: $this->userRepository->nextId(),
            createdAt: $now,
            updatedAt: $now,
            email: $command->email,
            roles: [$command->role],
            name: $command->name
        );
        $this->userRepository->add($user);
        $user->changePassword(
            $this->passwordHasher->hashPassword($user, $command->plainPassword)
        );

        $this->flusher->flush($user);

        return Result::success($user);
    }
}
