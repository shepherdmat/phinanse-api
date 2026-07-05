<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Foundation;

use Throwable;

final readonly class Response
{
    public const int HTTP_OK = 200;
    public const int HTTP_NOT_FOUND = 404;
    public const int HTTP_NOT_ALLOWED = 405;
    public const int HTTP_INTERNAL_SERVER_ERROR = 500;

    public function __construct(
        public string $content = '',
        public int    $statusCode = self::HTTP_OK,
        public ?array $headers = []
    )
    {
    }

    public static function jsonResponse(array $data, int $statusCode = 200, ?array $headers = []): self
    {
        try {
            $contentString = json_encode(
                value: $data,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (Throwable) {
            return self::jsonErrorResponse(
                message: 'Response data malformed.',
                statusCode: $statusCode,
            );
        }

        return new self(
            content: $contentString,
            statusCode: $statusCode,
            headers: $headers
        );
    }

    public static function jsonErrorResponse(string $message, ?int $statusCode = self::HTTP_INTERNAL_SERVER_ERROR): self
    {
        return new self(
            content: sprintf('{"error":"%s"}', $message),
            statusCode: $statusCode,
        );
    }

    public function send(): self
    {
        http_response_code(response_code: $this->statusCode);

        $headers = array_merge($this->headers, [
            'Content-Type' => 'application/json',
        ]);

        foreach ($headers as $name => $value) {
            header(
                header: sprintf('%s: %s', $name, $value),
                replace: false,
            );
        }

        echo $this->content;

        return $this;
    }
}
