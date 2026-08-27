<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Command\User;

use SensitiveParameter;
use Shepherdmat\Phinanse\Shared\Messenger\CommandMessageInterface;

final readonly class CreateUserCommand implements CommandMessageInterface
{
    public function __construct(
        public string $email,
        #[SensitiveParameter] public string $plainPassword,
    ) {
    }
}