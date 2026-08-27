<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Persistence\Mapper;

use DateMalformedStringException;
use DateTimeImmutable;
use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

class UserMapper
{
    /**
     * @throws DateMalformedStringException
     */
    public static function createFromDatabase(array $data): User
    {
        return new User(
            id: Uuid::fromString($data['id']),
            email: $data['email'],
            createdAt: new DateTimeImmutable($data['created_at']),
        );

    }
}