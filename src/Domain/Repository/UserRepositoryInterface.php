<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Repository;

use Shepherdmat\Phinanse\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findOneById(string $id): ?User;
}