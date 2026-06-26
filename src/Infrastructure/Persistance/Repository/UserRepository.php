<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Persistance\Repository;

use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function findOneById(string $id): ?User
    {
        return new User($id, 'test@test.com');
    }
}