<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Entity;

use DateTimeImmutable;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

final class User
{
    private Uuid $id {
        get {
            return $this->id;
        }
    }

    private string $email {
        get {
            return $this->email;
        }
        set {
            $this->email = $value;
        }
    }

    private DateTimeImmutable $createdAt {
        get {
            return $this->createdAt;
        }
    }

    public function __construct(
        Uuid              $id,
        string            $email,
        DateTimeImmutable $createdAt,
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->createdAt = $createdAt;
    }
}