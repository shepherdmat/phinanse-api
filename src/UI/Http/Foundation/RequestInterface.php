<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Foundation;

interface RequestInterface
{
    public function getMethod(): string;

    public function getUri(): string;

    public function getQueryParams(): array;

    public function getParsedBody(): array;

    public function getStringBodyParameter(string $key, ?string $default = null): ?string;

    public function getIntBodyParameter(string $key, int $default = 0): int;

    public function getFloatBodyParameter(string $key, float $default = 0.0): float;

    public function getBooleanBodyParameter(string $key, bool $default = false): bool;

    public function getArrayBodyParameter(string $key, array $default = []): array;
}