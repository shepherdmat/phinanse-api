<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Controller\User;

use InvalidArgumentException;
use Shepherdmat\Phinanse\Application\Exception\NotFoundException;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByEmailQuery;
use Shepherdmat\Phinanse\Application\Response\User\UserResponse;
use Shepherdmat\Phinanse\Shared\Messenger\MessageBusInterface;
use Shepherdmat\Phinanse\Shared\Messenger\MessageResponseInterface;
use Shepherdmat\Phinanse\Shared\ValueObject\Email;
use Shepherdmat\Phinanse\UI\Http\Exception\UiException;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Shepherdmat\Phinanse\UI\Http\Request\Resolver\LoginRequestResolver;

final readonly class LoginController
{
    public function __construct(
        private MessageBusInterface $messageBus,
    )
    {

    }

    /**
     * @throws UiException
     */
    public function __invoke(RequestInterface $request): MessageResponseInterface
    {
        $loginRequestModel = LoginRequestResolver::resolve(request: $request);

        dd($loginRequestModel);

        try {
            /** @var UserResponse $user */
            $user = $this->messageBus->query(new FindOneByEmailQuery(email: $loginRequestModel->email));

            //validacja hasła

        } catch (NotFoundException) {
            // invalid credentials
            throw UiException::userNotFoundByEmail($emailString);
        }


        dd($user);

        return $user;
    }
}