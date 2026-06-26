<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Query\User;

use Shepherdmat\Phinanse\Application\Response\User\UserResponse;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;

final readonly class FindOneByIdQueryHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    )
    {

    }

    public function __invoke(FindOneByIdQuery $query): UserResponse
    {
        $userEntity = $this->userRepository->findOneById(id: $query->id);

        return new UserResponse(
            id: $userEntity->),
            email: 'test@test.com',
        );
    }
}