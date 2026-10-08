<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\DependencyInjection;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final readonly class ClassLoader
{

    /**
     * @throws ReflectionException
     */
    public static function load(string $className, Container $container): object
    {
        $classReflection = new ReflectionClass($className);
        $constructor = $classReflection->getConstructor();

        if ($constructor === null) {
            return $classReflection->newInstance();
        }

        $arguments = array_map(
            static fn (ReflectionParameter $parameter) => self::resolveParameter($parameter, $container),
            $constructor->getParameters()
        );

        return $classReflection->newInstance(...$arguments);
    }

    private static function resolveParameter(ReflectionParameter $parameter, Container $container): mixed
    {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException(sprintf(
                'Parameter "$%s" in class "%s" requires a single named type hint.',
                $parameter->getName(),
                $parameter->getDeclaringClass()->getName()
            ));
        }

        if (self::isScalar($type->getName())) {
            return $container->getParameter(name: $parameter->getName());
        }

        return $container->get(id: $type->getName());
    }

    private static function isScalar(string $typeName): bool
    {
        return in_array($typeName, ['int', 'float', 'string', 'bool', 'array'], true);
    }
}