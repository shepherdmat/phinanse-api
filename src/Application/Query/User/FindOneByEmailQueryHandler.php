<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Query\User;

use Shepherdmat\Phinanse\Application\Exception\NotFoundException;
use Shepherdmat\Phinanse\Application\Response\User\UserResponse;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;

final readonly class FindOneByEmailQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {
    }

    public function __invoke(FindOneByEmailQuery $query): MessageResponseInterface
    {
        $user = $this->userRepository->findOneByEmail(email: $query->email);

        if (!$user) {
            throw new NotFoundException(message: sprintf('User with email "%s" does not exists.', $query->email));
        }

        return UserResponse::fromEntity($user);
    }
}