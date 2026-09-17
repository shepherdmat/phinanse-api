<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\ValueObject;

use InvalidArgumentException;

final readonly class Email
{
    public string $value;

    public function __construct(string $value)
    {
        $email = trim($value);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf('The string "%s" is not a valid email address.', $value));
        }

        $this->value = strtolower($email);
    }

    public static function fromString(string $value): self
    {
        return new self(value: $value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}