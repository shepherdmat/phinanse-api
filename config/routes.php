<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\UI\Http\Controller\v1\User\LoginController;
use Shepherdmat\Phinanse\UI\Http\Controller\v1\User\RefreshTokenController;

return [
    Request::METHOD_POST => [
        '/api/v1/auth/login' => [
            'class' => LoginController::class,
            'secure' => false,
        ],
        '/api/v1/auth/refresh' => [
            'controller' => RefreshTokenController::class,
            'secure' => false,
        ],
    ]
];
