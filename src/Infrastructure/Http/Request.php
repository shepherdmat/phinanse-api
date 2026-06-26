<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Http;

use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;

final readonly class Request implements RequestInterface
{
    public const string METHOD_POST = 'POST';

    public function __construct(
        public string  $uri,
        public string  $method,
        public array  $headers,
        public string $content
    ) {
    }

    public static function formGlobals(): self
    {
        return new self(
            uri: $_SERVER['REQUEST_URI'],
            method: $_SERVER['REQUEST_METHOD'],
            headers: getallheaders(),
            content: file_get_contents('php://input') ?: '',
        );
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        $uri = $this->uri;
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        return rawurldecode($uri);
    }
}
