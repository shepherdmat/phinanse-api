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


$projectDirectory = __DIR__ . '/../';
$env = @include sprintf('%s/.env.local.php', $projectDirectory);

if (!$env || !is_array($env)) {
    sendNativeJsonErrorResponse('Environment variables file not found or is not valid.');
}

if (!isset($env['environment'])) {
    sendNativeJsonErrorResponse('Crucial variable "environment" is not defined.');
}

$env['projectDirectory'] = $projectDirectory;

$envEnvironmentPath = sprintf('%s/.env.%s.php', $projectDirectory, $env['environment']);
$envEnvironment = @include $envEnvironmentPath;

if ($envEnvironment && is_array($envEnvironment)) {
    $env = array_merge($env, $envEnvironment);
}

$debug = $env['environment'] === 'dev';

if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

use Shepherdmat\Phinanse\Infrastructure\Container;
use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\UI\Http\HttpKernel;

//$container = Container::init(env: $env);
//
////dd($container);
//
///** @var \Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface $messageBus */
//$messageBus = $container->get(\Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface::class);
//
//dd($messageBus->query(new \Shepherdmat\Phinanse\Application\Query\User\FindOneByEmailQuery(\Shepherdmat\Phinanse\Shared\ValueObject\Email::fromString('mc.owczarek@gmail.com'))));
//
////$x = \Shepherdmat\Phinanse\Shared\ValueObject\Uuid::v7();
////
////var_dump($x->toBinary());die;
//
//HttpKernel::boot(container: Container::init(env: $env), debug: $debug)
//    ->handle(Request::formGlobals())
//    ->send();

$container = Container::init($env);
$request = Request::createFromGlobals();
$routes = require __DIR__ . '/../config/routes.php';

$kernel = new HttpKernel($container, $routes);
$kernel->handle($request);
