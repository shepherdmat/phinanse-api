<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\FileSystem;

use RuntimeException;

final readonly class ArrayFileLoader
{
    public static function load(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException(sprintf('Given path "%s" does not contain any file', $path));
        }

        $array = require $path;

        if (!is_array($array)) {
            throw new RuntimeException(sprintf('Array file must return an array: %s', $path));
        }

        return $array;
    }
}