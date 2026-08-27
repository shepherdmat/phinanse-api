<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Security;

use Shepherdmat\Phinanse\Application\Security\PasswordHasherInterface;
use SensitiveParameter;

final readonly class NativeArgon2idPasswordHasher implements PasswordHasherInterface
{
    public function hash(#[SensitiveParameter] string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2ID);
    }

    public function verify(#[SensitiveParameter] string $plainPassword, string $passwordHash): bool
    {
        return password_verify($plainPassword, $passwordHash);
    }
}