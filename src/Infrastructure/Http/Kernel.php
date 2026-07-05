<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Http;

use Shepherdmat\Phinanse\Infrastructure\Container;
use Shepherdmat\Phinanse\UI\Http\Foundation\Response;

final readonly class Kernel
{
    public function __construct(
        private Container $container,
    ) {}

    public static function boot(Container $container): self
    {
        return new self(
            container: $container,
        );
    }

    public function handle(Request $request): Response
    {
        try {
            /** @var Router $router */
            $router = $this->container->get(Router::class);

            return $router
                ->dispatch($request);
        } catch (\Throwable $e) {
            return Response::jsonErrorResponse(
                message: $this->container->isDebugMode() ? $e->getMessage() : 'Internal Server Error',
                statusCode: $this->container->isDebugMode() ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    private function getRouter()
    {

    }
}
