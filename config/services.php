<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Infrastructure\Messenger\MessageBus;
use Shepherdmat\Phinanse\Infrastructure\Persistence\MySqlConnection;
use Shepherdmat\Phinanse\Infrastructure\Persistence\Repository\UserRepository;
use Shepherdmat\Phinanse\Infrastructure\Security\Argon2PasswordHasher;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Persistence\ConnectionInterface as DatabaseConnection;
use Shepherdmat\Phinanse\Shared\Security\PasswordHasherInterface;

return [
    DatabaseConnection::class => MySqlConnection::class,
    UserRepositoryInterface::class => UserRepository::class,
    PasswordHasherInterface::class => Argon2PasswordHasher::class,
    MessageBusInterface::class => MessageBus::class,
];