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

final readonly class Container implements ContainerInterface
{
    public function __construct(
        private array $services = [],
    ) {
    }

    public static function init(array $env): self
    {
        $repositories = self::getRepositories($env);
        $passwordHasher = new NativeArgon2idPasswordHasher();

        $baseServices = array_merge($repositories, [
            PasswordHasherInterface::class => $passwordHasher,
        ]);

        $tempContainer = new self($baseServices);
        $messages = require __DIR__ . '/../../config/messages.php';

        $handlers = array_map(function ($factoryClosure) use ($tempContainer) {
            return $factoryClosure($tempContainer);
        }, $messages['handlers'] ?? []);

        $servicesWithHandlers = array_merge($baseServices, $handlers);
        $containerForBus = new self($servicesWithHandlers);

        $messageBus = new MessageBus($containerForBus, $messages['routing']);

        $finalServices = array_merge($servicesWithHandlers, [
            MessageBusInterface::class => $messageBus,
            ContainerInterface::class => $containerForBus,
        ]);

        return new self($finalServices);
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function get(string $id): object
    {
        if (!$this->has($id)) {
            throw new InvalidArgumentException(sprintf('Service "%s" not found in container.', $id));
        }

        return $this->services[$id];
    }

    private static function getRepositories(array $env): array
    {
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

        return [
            UserRepositoryInterface::class => new UserRepository($connection),
        ];
    }
}