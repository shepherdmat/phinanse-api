<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Entity;

use DateTimeImmutable;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

final class User
{
    private(set) Uuid $id;

    public string $email {
        get {
            return $this->email;
        }
        set {
            $this->email = $value;
        }
    }

    private(set) string $passwordHash;

    private(set) DateTimeImmutable $createdAt;

    public function __construct(
        Uuid              $id,
        string            $email,
        string            $passwordHash,
        DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->createdAt = $createdAt;
    }

    public function changePassword(string $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
    }
}