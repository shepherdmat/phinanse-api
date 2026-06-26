<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Messenger;

use Shepherdmat\Phinanse\Infrastructure\Container;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;
use Shepherdmat\Phinanse\Shared\Messenger\QueryMessageInterface;

final readonly class MessageBus implements MessageBusInterface
{
    public function __construct(
        private Container $container,
        private array $routing,
    )
    {
    }

    public function query(QueryMessageInterface $query): MessageResponseInterface
    {
        $handlerClass = $this->routing[$query::class] ?? null;

        if (!$handlerClass) {
            throw new \Exception(sprintf('Handler not found for query "%s"', $query::class));
        }

        $handler = $this->container->get($handlerClass);

        return $handler($query);
    }
}