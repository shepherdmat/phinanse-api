<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Application\Command\User\CreateUserCommand;
use Shepherdmat\Phinanse\Application\Command\User\CreateUserCommandHandler;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQuery;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQueryHandler;
use Shepherdmat\Phinanse\Application\Security\PasswordHasherInterface;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Infrastructure\Container;

return [
    'routing' => [
        CreateUserCommand::class => [
            'handler' => CreateUserCommandHandler::class,
            'async' => false,
        ],
        FindOneByIdQuery::class => [
            'handler' => FindOneByIdQueryHandler::class,
            'async' => false,
        ],
    ],

    'handlers' => [
        CreateUserCommandHandler::class => static function (Container $c): CreateUserCommandHandler {
            return new CreateUserCommandHandler(
                userRepository: $c->get(UserRepositoryInterface::class),
                passwordHasher: $c->get(PasswordHasherInterface::class)
            );
        },

        FindOneByIdQueryHandler::class => static function (Container $c): FindOneByIdQueryHandler {
            return new FindOneByIdQueryHandler(
                userRepository: $c->get(UserRepositoryInterface::class)
            );
        }
    ],
];