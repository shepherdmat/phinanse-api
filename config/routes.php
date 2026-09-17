<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\UI\Http\Controller\User\LoginController;
use Shepherdmat\Phinanse\UI\Http\Controller\User\RefreshTokenController;

return [
    Request::METHOD_POST => [
        '/api/auth/login' => [
            'class' => LoginController::class,
            'secure' => false,
        ],
        '/api/auth/refresh' => [
            'controller' => RefreshTokenController::class,
            'secure' => false,
        ],
    ]
];
