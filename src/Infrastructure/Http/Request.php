<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Http;

use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;

final readonly class Request implements RequestInterface
{
    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';
    public const string METHOD_PUT = 'PUT';
    public const string METHOD_DELETE = 'DELETE';
    public const string METHOD_PATCH = 'PATCH';

    public function __construct(
        private string $method,
        private string $uri,
        private array $queryParams = [],
        private array $parsedBody = [],
    ) {
    }

    /**
     * Buduje obiekt Request na podstawie globalnego stanu PHP.
     */
    public static function createFromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_GET;
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = parse_url($uri, PHP_URL_PATH);
        $parsedBody = $_POST;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $rawBody = file_get_contents('php://input');
            $decodedBody = json_decode($rawBody, true);

            if (is_array($decodedBody)) {
                $parsedBody = $decodedBody;
            }
        }

        return new self(
            method: strtoupper($method),
            uri: $uri,
            queryParams: $_GET,
            parsedBody: $parsedBody
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }

    /**
     * Zwraca wartość jako string.
     */
    public function getStringBodyParameter(string $key, ?string $default = null): ?string
    {
        if (!array_key_exists($key, $this->parsedBody)) {
            return $default;
        }

        // Rzutowanie na string zabezpiecza przed błędem typowania w przypadku np. przekazania liczby
        return (string) $this->parsedBody[$key];
    }

    /**
     * Zwraca wartość jako integer.
     */
    public function getIntBodyParameter(string $key, int $default = 0): int
    {
        if (!array_key_exists($key, $this->parsedBody)) {
            return $default;
        }

        return (int) $this->parsedBody[$key];
    }

    /**
     * Zwraca wartość jako float.
     */
    public function getFloatBodyParameter(string $key, float $default = 0.0): float
    {
        if (!array_key_exists($key, $this->parsedBody)) {
            return $default;
        }

        return (float) $this->parsedBody[$key];
    }

    /**
     * Zwraca wartość jako boolean.
     * Używa filter_var, by poprawnie obsłużyć wartości takie jak 'true', '1', 'on', 'yes'.
     */
    public function getBooleanBodyParameter(string $key, bool $default = false): bool
    {
        if (!array_key_exists($key, $this->parsedBody)) {
            return $default;
        }

        return filter_var($this->parsedBody[$key], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Zwraca wartość jako tablicę.
     */
    public function getArrayBodyParameter(string $key, array $default = []): array
    {
        if (!array_key_exists($key, $this->parsedBody) || !is_array($this->parsedBody[$key])) {
            return $default;
        }

        return $this->parsedBody[$key];
    }
}