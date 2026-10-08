<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Messenger;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shepherdmat\Phinanse\Infrastructure\DependencyInjection\ClassLoader;
use Shepherdmat\Phinanse\Infrastructure\DependencyInjection\Container;
use Shepherdmat\Phinanse\Infrastructure\FileSystem\ArrayFileLoader;
use Shepherdmat\Phinanse\Shared\Messenger\CommandMessageInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;
use Shepherdmat\Phinanse\Shared\Messenger\QueryMessageInterface;

final readonly class MessageBus implements MessageBusInterface
{
    private array $routing;

    public function __construct(
        private Container $container,
    )
    {
        $this->routing = self::getConfig($this->container);
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function query(QueryMessageInterface $query): MessageResponseInterface
    {
        $route = $this->getRouteConfig($query::class);

        return $this->loadHandler($route['handler'])($query);
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

        return $this->loadHandler($route['handler'])($command);
    }

    private static function getConfig(Container $container): array
    {
        return ArrayFileLoader::load(
            sprintf('%s/config/messages.php', $container->getParameter(name: 'projectDirectory'))
        );
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

    private function loadHandler(string $handlerClass): callable
    {
        return ClassLoader::load($handlerClass, $this->container);
    }
}