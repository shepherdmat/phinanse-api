<?php

declare(strict_types=1);

function sendNativeJsonErrorResponse(string $message): void
{
    header(
        header: 'Content-Type: application/json',
        response_code: 500,
    );

    echo sprintf('{"error":"%s"}', $message);
    exit;
}

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    sendNativeJsonErrorResponse('Vendor directory not found. Please run composer install.');
}

require_once __DIR__ . '/../vendor/autoload.php';

use Shepherdmat\Phinanse\Infrastructure\DependencyInjection\Container;
use Shepherdmat\Phinanse\Infrastructure\FileSystem\EnvironmentVariablesLoader;
use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\UI\Http\HttpKernel;

$env = EnvironmentVariablesLoader::load(projectDirectory: dirname(__DIR__));
$debug = $env['debug'] ?? false;

if ($debug) {
    ini_set(option: 'display_errors', value: '1');
    ini_set(option: 'display_startup_errors', value: '1');
    error_reporting(error_level: E_ALL);
}

$container = Container::init(environmentVariables: $env);

$kernel = HttpKernel::boot(
    container: $container,
    debug: $debug,
);

$kernel->handle(request: Request::createFromGlobals());
