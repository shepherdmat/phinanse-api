<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Security;

use SensitiveParameter;
use Shepherdmat\Phinanse\Shared\Security\PasswordHasherInterface;

final readonly class Argon2PasswordHasher implements PasswordHasherInterface
{
    public function hash(#[SensitiveParameter] string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 1,
        ]);
    }

    public function verify(#[SensitiveParameter] string $plainPassword, string $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }
}