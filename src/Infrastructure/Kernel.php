<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure;

use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\Infrastructure\Http\Response;
use Shepherdmat\Phinanse\Infrastructure\Http\Router;

final readonly class Kernel
{
    public const string ENVIRONMENT_DEV = 'dev';

    public function __construct(
        private Container $container,
        private string $environment,
    ) {}

    public static function boot(string $environment): self
    {
        $container = Container::init(environment: $environment);

        return new self(
            container: $container,
            environment: $environment,
        );
    }

    public function handleRequest(Request $request): Response
    {
        try {
            /** @var Router $router */
            $router = $this->container->get(Router::class);

            return $router
                ->dispatch($request);
        } catch (\Throwable $e) {
            return Response::json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
