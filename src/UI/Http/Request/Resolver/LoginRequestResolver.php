<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Request\Resolver;

use Shepherdmat\Phinanse\Shared\ValueObject\Email;
use Shepherdmat\Phinanse\UI\Http\Exception\UiException;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Shepherdmat\Phinanse\UI\Http\Request\Model\LoginRequestModel;

final readonly class LoginRequestResolver
{
    public static function resolve(RequestInterface $request): LoginRequestModel
    {
        $emailString = $request->getStringBodyParameter(key: 'email');

        if (!$emailString) {
            throw new UiException('Email is required');
        }

        try {
            $email = Email::fromString(value: $emailString);
        } catch (InvalidArgumentException) {
            throw UiException::invalidEmail($emailString);
        }

        return new LoginRequestModel(
            email: $email,
            password: $request->getStringBodyParameter(key: 'password')
        );
    }
}