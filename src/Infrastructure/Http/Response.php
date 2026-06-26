<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Http;

use JsonException;

use Shepherdmat\Phinanse\UI\Http\Foundation\ResponseInterface;

final readonly class Response implements ResponseInterface
{
    public function __construct(
        public string $content = '',
        public int    $statusCode = 200,
        public array  $headers = []
    ) {}

    public static function json(array $data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';

        return new self(json_encode($data, JSON_THROW_ON_ERROR), $statusCode, $headers);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }

        echo $this->content;
    }
}
