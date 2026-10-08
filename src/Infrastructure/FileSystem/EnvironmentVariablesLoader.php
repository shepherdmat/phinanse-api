<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\FileSystem;

use RuntimeException;

final class EnvironmentVariablesLoader
{
    /**
     * @throws RuntimeException
     */
    public static function load(string $projectDirectory): array
    {
        $localEnvPath = sprintf('%s/.env.local.php', $projectDirectory);

        if (!file_exists($localEnvPath)) {
            throw new RuntimeException(sprintf('Environment variables file not found: %s', $localEnvPath));
        }

        $localEnv = require $localEnvPath;

        if (!is_array($localEnv)) {
            throw new RuntimeException('Environment variables file must return an array.');
        }

        if (!isset($localEnv['environment'])) {
            throw new RuntimeException('Crucial variable "environment" is not defined.');
        }

        $localEnv['projectDirectory'] = $projectDirectory;

        $instanceEnvironmentPath = sprintf('%s/.env.%s.php', $projectDirectory, $localEnv['environment']);

        $instanceEnvironment = [];
        if (file_exists($instanceEnvironmentPath)) {
            $instanceEnvironment = require $instanceEnvironmentPath;

            if (!is_array($instanceEnvironment)) {
                throw new RuntimeException(sprintf('Instance environment file must return an array: %s', $instanceEnvironmentPath));
            }
        }

        return array_merge($localEnv, $instanceEnvironment);
    }
}