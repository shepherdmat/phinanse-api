<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Controller\v1\User;

use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQuery;
use Shepherdmat\Phinanse\Application\Response\User\UserResponse;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Shepherdmat\Phinanse\UI\Http\Foundation\ResponseInterface;

final readonly class LoginController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ResponseInterface $response,
    )
    {

    }

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        /** @var UserResponse $user */
        $user = $this->messageBus->query(new FindOneByIdQuery('123'));

        return $this->response->jsonResponse($user->toArray());
    }
}