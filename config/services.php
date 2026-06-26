<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Infrastructure\Http\Request;
use Shepherdmat\Phinanse\Infrastructure\Http\Response;
use Shepherdmat\Phinanse\Infrastructure\Http\Router;
use Shepherdmat\Phinanse\Infrastructure\Persistance\Repository\UserRepository;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Shepherdmat\Phinanse\UI\Http\Foundation\ResponseInterface;

return [
    'services' => [
        Router::class,
    ],
    'bindings' => [
        RequestInterface::class => Request::class,
        ResponseInterface::class => Response::class,

        UserRepositoryInterface::class => UserRepository::class,
    ],
];
