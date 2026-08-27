<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\ValueObject;

use InvalidArgumentException;
use Random\RandomException;

final readonly class Uuid
{
    public function __construct(private string $value)
    {
        if (!self::isValid(uuid: $value)) {
            throw new InvalidArgumentException(
                sprintf('"%s" is not a valid UUID v7 value.', $value)
            );
        }
    }

    public static function fromString(string $value): self
    {
        return new self(value: $value);
    }

    public static function fromBinary(string $bytes): self
    {
        if (strlen($bytes) !== 16) {
            throw new InvalidArgumentException('Binary UUID must be exactly 16 bytes.');
        }

        $uuidString = $bytes
                |> bin2hex(...)
                |> (fn($x) => str_split($x, 4))
                |> (fn($x) => vsprintf('%s%s-%s-%s-%s-%s%s%s', $x));

        return self::fromString(value: $uuidString);
    }

    public static function v7(): self
    {
        $timestamp = (int) (microtime(as_float: true) * 1000);
        $timeHex = str_pad(dechex($timestamp), length: 12, pad_string: '0', pad_type: STR_PAD_LEFT);

        $data = hex2bin($timeHex) . random_bytes(length: 10);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x70);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return self::fromBinary(bytes: $data);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toBinary(): string
    {
        return hex2bin(str_replace('-', '', $this->value));
    }

    public function equals(Uuid $other): bool
    {
        return $this->value === $other->toString();
    }

    public static function isValid(string $uuid): bool
    {
        return preg_match(
                pattern: '/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                subject: $uuid
            ) === 1;
    }
}