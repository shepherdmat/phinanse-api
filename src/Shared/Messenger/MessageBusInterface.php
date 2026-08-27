<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\Messenger;

interface MessageBusInterface
{
    public function query(QueryMessageInterface $query): MessageResponseInterface;
    public function command(CommandMessageInterface $command): ?MessageResponseInterface;
}