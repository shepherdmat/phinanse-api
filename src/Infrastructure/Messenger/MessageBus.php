<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Messenger;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shepherdmat\Phinanse\Infrastructure\Container;
use Shepherdmat\Phinanse\Shared\Messenger\CommandMessageInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;
use Shepherdmat\Phinanse\Shared\Messenger\QueryMessageInterface;
use Exception;

final readonly class MessageBus implements MessageBusInterface
{
    public function __construct(
        private Container $container,
        private array $routing,
    ) {
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function query(QueryMessageInterface $query): MessageResponseInterface
    {
        $route = $this->getRouteConfig($query::class);
        $handler = $this->container->get($route['handler']);

        return $handler($query);
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function command(CommandMessageInterface $command): ?MessageResponseInterface
    {
        $route = $this->getRouteConfig($command::class);
        $isAsync = $route['async'] ?? false;

        if ($isAsync) {
            // === PRZYSZŁA ASYNCHRONICZNOŚĆ ===
        }

        $handler = $this->container->get($route['handler']);
        return $handler($command);
    }

    /**
     * @throws Exception
     */
    private function getRouteConfig(string $messageClass): array
    {
        $config = $this->routing[$messageClass] ?? null;

        if (!$config) {
            throw new Exception(sprintf('Route configuration not found for message "%s"', $messageClass));
        }

        if (is_string($config)) {
            $config = [
                'handler' => $config,
                'async' => false,
            ];
        }

        if (!isset($config['handler'])) {
            throw new Exception(sprintf('Handler class is missing in route configuration for message "%s"', $messageClass));
        }

        return $config;
    }
}