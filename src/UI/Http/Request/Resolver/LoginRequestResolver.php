<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Request\Resolver;

use Shepherdmat\Phinanse\Shared\Exception\InvalidArgumentException;
use Shepherdmat\Phinanse\Shared\ValueObject\Email;
use Shepherdmat\Phinanse\UI\Http\Exception\UiValidationException;
use Shepherdmat\Phinanse\UI\Http\Foundation\RequestInterface;
use Shepherdmat\Phinanse\UI\Http\Request\Dto\LoginRequestDto;

final readonly class LoginRequestResolver
{
    /**
     * @throws UiValidationException
     */
    public static function resolve(RequestInterface $request): LoginRequestDto
    {
        $emailString = $request->getStringBodyParameter(key: 'email');
        $passwordString = $request->getStringBodyParameter(key: 'password');

        $errors = [];

        if (!$emailString) {
            $errors['email'] = 'error.email.required';
        }

        if (!$passwordString) {
            $errors['password'] = 'error.password.required';
        }

        if (!empty($errors)) {
            throw UiValidationException::withErrors($errors);
        }

        try {
            $email = Email::fromString(value: $emailString);
        } catch (InvalidArgumentException) {
            throw UiValidationException::withErrors(['email' => 'error.email.invalid']);
        }

        return new LoginRequestDto(
            email: $email,
            password: $passwordString
        );
    }
}