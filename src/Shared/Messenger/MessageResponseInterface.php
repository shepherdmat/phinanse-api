<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\Messenger;

interface MessageResponseInterface
{
    public function toArray(): array;
}