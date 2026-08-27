<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Command\User;

use Shepherdmat\Phinanse\Application\Security\PasswordHasherInterface;
use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

final readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $passwordHash = $this->passwordHasher->hash($command->plainPassword);

        $user = new User(
            id: Uuid::v7(),
            email: $command->email,
            passwordHash: $passwordHash,
            createdAt: new \DateTimeImmutable(),
        );

        $this->userRepository->save($user);
    }
}