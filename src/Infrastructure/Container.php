<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Shepherdmat\Phinanse\Application\Security\PasswordHasherInterface;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Infrastructure\Messenger\MessageBus;
use Shepherdmat\Phinanse\Infrastructure\Persistence\MySqlConnection;
use Shepherdmat\Phinanse\Infrastructure\Persistence\Repository\UserRepository;
use Shepherdmat\Phinanse\Infrastructure\Security\NativeArgon2idPasswordHasher;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;

final class Container implements ContainerInterface
{
    private array $instances = [];

    public function __construct(
        private readonly array $factories = [],
    ) {
    }

    public static function init(array $env): self
    {
        // 1. Zamiast gotowych obiektów, definiujemy FABRYKI (closures)
        $factories = [
            UserRepositoryInterface::class => static function (self $c) use ($env) {
                return self::createUserRepository($env);
            },
            PasswordHasherInterface::class => static fn() => new NativeArgon2idPasswordHasher(),
        ];

        // 2. Pobieramy konfigurację (handlery już masz zapisane jako fabryki, co jest świetne!)
        $messages = require __DIR__ . '/../../config/messages.php';

        $factories = array_merge($factories, $messages['handlers'] ?? []);

        // 3. Fabryka dla MessageBusa
        $factories[MessageBusInterface::class] = static function (self $c) use ($messages) {
            // Bus dostaje czysty kontener i config routingu
            return new MessageBus($c, $messages['routing']);
        };

        // Opcjonalnie: Jeśli coś jawnie potrzebuje ContainerInterface, po prostu zwracamy $c.
        $factories[ContainerInterface::class] = static fn(self $c) => $c;

        // Tworzymy kontener RAZ.
        return new self($factories);
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]) || isset($this->instances[$id]);
    }

    public function get(string $id): object
    {
        // Jeśli serwis został już wcześniej stowrzony, zwróć go (Singleton)
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->factories[$id])) {
            throw new InvalidArgumentException(sprintf('Service "%s" not found in container.', $id));
        }

        // Wywołaj fabrykę, przekazując instancję kontenera ($this)
        $factory = $this->factories[$id];
        $instance = $factory($this);

        // Zapisz na przyszłość
        $this->instances[$id] = $instance;

        return $instance;
    }

    private static function createUserRepository(array $env): UserRepositoryInterface
    {
        // Ta logika wykona się DOPIERO gdy ktoś wywoła $container->get(UserRepositoryInterface::class)
        $dbHost = $env['database_host'] ?? false;
        $dbPort = $env['database_port'] ?? false;
        $dbCharset = $env['database_charset'] ?? false;
        $dbName = $env['database_name'] ?? false;
        $dbUser = $env['database_user'] ?? false;
        $dbPassword = $env['database_password'] ?? false;

        if (!$dbHost || !$dbName || !$dbUser || !$dbPassword || !$dbPort || !$dbCharset) {
            throw new InvalidArgumentException('Database connection parameters are not set.');
        }

        $connection = new MySqlConnection(
            host: $dbHost,
            port: (int) $dbPort,
            charset: $dbCharset,
            database: $dbName,
            username: $dbUser,
            password: $dbPassword,
        );

        return new UserRepository($connection);
    }
}