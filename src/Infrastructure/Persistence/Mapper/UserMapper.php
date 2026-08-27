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
    public static function fromDatabase(array $data): User
    {
        return new User(
            id: Uuid::fromBinary($data['id']),
            email: $data['email'],
            passwordHash: $data['password_hash'],
            createdAt: new DateTimeImmutable($data['created_at'])
        );
    }

    public static function toDatabase(User $user): array
    {
        return [
            'id' => $user->id->toBinary(),
            'email' => $user->email,
            'password_hash' => $user->passwordHash,
            'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}