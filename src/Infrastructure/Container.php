<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure;

use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use Shepherdmat\Phinanse\Infrastructure\Http\Router;
use Shepherdmat\Phinanse\Infrastructure\Messenger\MessageBus;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;

final class Container
{
    public static function init(array $env): self
    {
        $services = require __DIR__ . '/../../config/services.php';
        $routes = require __DIR__ . '/../../config/routes.php';
        $messages = require __DIR__ . '/../../config/messages.php';

        $container = new self(env: $env);
        $container->set(self::class, $container);

        foreach ($services['services'] as $serviceClass) {
            $container->set($serviceClass, $serviceClass);
        }

        foreach ($services['bindings'] as $bindClass => $serviceClass) {
            $container->bind($bindClass, $serviceClass);
        }

        $container->set(MessageBusInterface::class, function (Container $container) use ($messages) {
            return new MessageBus($container, $messages['routing']);
        });

        /** @var Router $router */
        $router = $container->get(Router::class);
        foreach ($routes as $method => $pathHandlers) {
            foreach ($pathHandlers as $path => $handlerData) {
                $handler = $handlerData['class'] ?? $handlerData['controller'] ?? null;
                if ($handler) {
                    $router->addRoute($method, $path, [$handler, '__invoke']);
                }
            }
        }

        return $container;
    }

    public function __construct(
        private readonly array $env,
        private array $instances = [],
        private array $bindings = [],
    )
    {
    }

    public function isDebugMode(): bool
    {
        return $this->env['debug'] ?? false;
    }

    public function bind(string $id, callable|string $concrete): void
    {
        $this->bindings[$id] = $concrete;
    }

    public function set(string $id, callable|object|string $concrete): void
    {
        $this->instances[$id] = $concrete;
    }

    public function has(string $id): bool
    {
        return isset($this->instances[$id]) || isset($this->bindings[$id]) || class_exists($id);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            $instance = $this->instances[$id];
            if (is_callable($instance) && !(is_string($instance) && class_exists($instance))) {
                $this->instances[$id] = $instance($this);
            } elseif (is_string($instance) && class_exists($instance)) {
                $this->instances[$id] = $this->resolve($instance);
            }
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];

            if (is_callable($concrete)) {
                return $concrete($this);
            }

            if (is_string($concrete) && class_exists($concrete)) {
                return $this->resolve($concrete);
            }
        }

        if (class_exists($id)) {
            return $this->resolve($id);
        }

        throw new Exception("No entry or class found for '{$id}' in Container");
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function resolve(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class();
        }

        $parameters = $constructor->getParameters();
        $dependencies = array_map(function (ReflectionParameter $param) use ($class) {
            $type = $param->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                $paramName = $param->getName();

                if ($param->isDefaultValueAvailable()) {
                    return $param->getDefaultValue();
                }

                throw new Exception("Cannot auto-wire parameter '{$paramName}' of type '{$type}' in {$class}. Ensure it is bound in the container or has a default value.");
            }

            return $this->get($type->getName());
        }, $parameters);

        return $reflection->newInstanceArgs($dependencies);
    }
}