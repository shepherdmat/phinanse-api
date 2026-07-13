<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\ValueObject;

use InvalidArgumentException;

final readonly class Uuid
{
    public function __construct(private string $value)
    {
        if (!self::isValid(uuid: $value)) {
            throw new InvalidArgumentException(
                sprintf('"%s" is not a UUID v4 value.', $value)
            );
        }
    }

    public static function fromString(string $value): self
    {
        return new self(value: $value);
    }

    public static function v4(): self
    {
        $data = random_bytes(length: 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        $uuidString = $data
                |> bin2hex(...)
                |> (fn($x) => str_split($x, 4))
                |> (fn($x) => vsprintf('%s%s-%s-%s-%s-%s%s%s', $x));

        return self::fromString($uuidString);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(Uuid $other): bool
    {
        return $this->value === $other->toString();
    }

    public function isValid(string $uuid): bool
    {
        return preg_match(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $uuid
            ) === 1;
    }
}
