<?php

declare(strict_types=1);

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'Vendor directory not found. Please run composer install.']);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\Infrastructure\Kernel;

$env = $_SERVER['APP_ENV'] ?? Kernel::ENVIRONMENT_DEV;

if ($env === Kernel::ENVIRONMENT_DEV) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

$kernel = Kernel::boot(environment: $env);
$response = $kernel->handleRequest(Request::formGlobals());
$response->send();

