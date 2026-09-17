<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use RuntimeException;
use Shepherdmat\Phinanse\Shared\Exception\NotFoundExceptionInterface;
use Shepherdmat\Phinanse\UI\Http\Exception\UiException;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Throwable;

final readonly class HttpKernel
{
    public function __construct(
        private ContainerInterface $container,
        private array              $routes,
    )
    {
    }

    public function handle(RequestInterface $request): void
    {
        try {
            $method = $request->getMethod();
            $uri = $request->getUri();
            $routeParams = [];
            $routeConfig = $this->matchRoute($method, $uri, $routeParams);

            if ($routeConfig === null) {
                throw new UiException('error.route.not_found', [], 404, 'Route not found');
            }

            $controllerClass = $routeConfig['class'] ?? $routeConfig['controller'];
            $controller = $this->resolveController($controllerClass);
            $response = $controller($request, ...$routeParams);

            $this->sendJson(200, ['data' => $response]);

        } catch (UiException $e) {
            $this->sendJson($e->statusCode, [
                'message' => $e->getMessage(),
                'statusCode' => $e->statusCode,
                'translationKey' => $e->translationKey,
                'translationParams' => $e->translationParams,
            ]);
        } catch (NotFoundExceptionInterface $e) {
            $this->sendJson(404, ['error' => ['message' => $e->getMessage()]]);
        } catch (Throwable $e) {
            $this->sendJson(500, ['error' => ['message' => 'Internal Server Error: ' . $e->getMessage()]]);
        }
    }

    private function matchRoute(string $method, string $uri, &$params = []): ?array
    {
        $routesForMethod = $this->routes[$method] ?? [];

        foreach ($routesForMethod as $path => $config) {
            if ($path === $uri) {
                return $config;
            }
            if (str_contains($path, '{')) {
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<$1>[0-9a-fA-F\-]{36})', $path);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    return $config;
                }
            }
        }

        return null;
    }

    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    private function resolveController(string $controllerClass): object
    {
        if ($this->container->has($controllerClass)) {
            return $this->container->get($controllerClass);
        }

        $reflection = new ReflectionClass($controllerClass);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $controllerClass();
        }

        $dependencies = array_map(function (ReflectionParameter $param) {
            $type = $param->getType();
            if (!$type || $type->isBuiltin()) {
                throw new RuntimeException(sprintf('Cannot auto-wire parameter "$%s" in %s', $param->getName(), $param->getDeclaringClass()->getName()));
            }

            return $this->container->get($type->getName());
        }, $constructor->getParameters());

        return $reflection->newInstanceArgs($dependencies);
    }

    private function sendJson(int $statusCode, array $data): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}