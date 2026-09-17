<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Repository;

use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Shared\ValueObject\Email;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

interface UserRepositoryInterface
{
    public function findOneById(Uuid $id): ?User;

    public function findOneByEmail(Email $email): ?User;

    public function save(User $user): void;
}