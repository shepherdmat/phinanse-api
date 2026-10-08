<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Application\Command\User\CreateUserCommand;
use Shepherdmat\Phinanse\Application\Command\User\CreateUserCommandHandler;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByEmailQuery;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByEmailQueryHandler;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQuery;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQueryHandler;

return [
    CreateUserCommand::class => [
        'handler' => CreateUserCommandHandler::class,
        'async' => false,
    ],
    FindOneByIdQuery::class => [
        'handler' => FindOneByIdQueryHandler::class,
        'async' => false,
    ],
    FindOneByEmailQuery::class => [
        'handler' => FindOneByEmailQueryHandler::class,
        'async' => false,
    ],
];