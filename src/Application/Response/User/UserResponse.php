<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Response\User;

use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;

final class UserResponse implements MessageResponseInterface
{
    public function __construct(
        public string $id,
        public string $email,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }
}