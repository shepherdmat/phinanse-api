<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\DependencyInjection;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Shepherdmat\Phinanse\Infrastructure\FileSystem\ArrayFileLoader;

final class Container implements ContainerInterface
{
    private array $instances = [];

    public function __construct(
        private readonly array $parameters,
        private readonly array $services,
    )
    {
    }

    public static function init(array $environmentVariables): self
    {
        return new Container(
            parameters: $environmentVariables,
            services: ArrayFileLoader::load(path: sprintf('%s/config/services.php', $environmentVariables['projectDirectory'])),
        );
    }

    public function getParameter(string $name): mixed
    {
        /**
         * @todo sprawdź czy istnieje
         */
        return $this->parameters[$name];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function isLoaded(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    /**
     * @todo Poprawna obsługa błędów
     */
    public function get(string $id): object
    {
        if ($this->isLoaded(id: $id)) {
            return $this->instances[$id];
        }

        if (!$this->has(id: $id)) {
            if ($id === self::class) {
                return $this;
            }

            throw new InvalidArgumentException(sprintf('Service configuration for "%s" is not defined.', $id));
        }

        $service = ClassLoader::load(className: $this->services[$id], container: $this);
        $this->instances[$id] = $service;

        return $service;
    }
}
