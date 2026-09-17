<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Response\User;

use DateTimeImmutable;
use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;
use Shepherdmat\Phinanse\Shared\ValueObject\Email;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

final class UserResponse implements MessageResponseInterface
{
    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->id,
            email: $user->email,
            createdAt: $user->createdAt,
        );
    }

    public function __construct(
        public Uuid $id,
        public Email $email,
        public DateTimeImmutable $createdAt,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => (string) $this->id,
            'email' => (string) $this->email,
        ];
    }
}