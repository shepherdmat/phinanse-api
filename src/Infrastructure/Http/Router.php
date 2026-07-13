<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Http;

use Shepherdmat\Phinanse\Infrastructure\Container;
use Shepherdmat\Phinanse\UI\Http\Foundation\Response;

final class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Container $container,
    )
    {
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        if (!isset($this->routes[$method])) {
            return Response::jsonErrorResponse(
                message: 'Method Not Allowed',
                statusCode: Response::HTTP_NOT_ALLOWED,
            );
        }

        foreach ($this->routes[$method] as $regex => $handler) {
            if (preg_match($regex, $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_array($handler) && count($handler) === 2) {
                    [$class, $methodName] = $handler;
                    // Resolve the controller from the container
                    $controller = $this->container->get($class);
                    $handler = [$controller, $methodName];
                }

                // Add the request as the first argument, followed by the route parameters
                $result = call_user_func_array($handler, array_merge([$request], array_values($params)));

                if ($result instanceof Response) {
                    return $result;
                }

                return Response::jsonResponse(['result' => $result]);
            }
        }

        return Response::jsonErrorResponse(
            message: 'Resource Not Found.',
            statusCode: Response::HTTP_NOT_FOUND,
        );
    }

    public function addRoute(string $method, string $path, callable|array $handler): void
    {
        // Convert route with parameters (e.g., /users/{id}) to a regular expression
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_\-]+)', $path);
        // Ensure exact match
        $regex = '#^' . $regex . '$#';

        $this->routes[$method][$regex] = $handler;
    }
}
