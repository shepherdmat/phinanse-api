<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Repository;

use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

interface UserRepositoryInterface
{
    public function findOneById(Uuid $id): ?User;
}