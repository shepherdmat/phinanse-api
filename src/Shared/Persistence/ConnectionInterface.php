<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Shared\Persistence;

interface ConnectionInterface
{
    public function fetch(string $sql, array $params = []): ?array;

    public function execute(string $sql, array $params = []): int;
}