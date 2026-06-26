<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Domain\Entity;

final class User
{
    private string $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
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

    public function __construct(
        string $id,
        string $email,
    )
    {
        $this->id = $id;
        $this->email = $email;
    }
}