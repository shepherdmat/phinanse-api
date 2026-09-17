<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Exception;

use Exception;

final class UiException extends Exception
{
    public function __construct(
        public readonly string $translationKey,
        public readonly array $translationParams = [],
        public readonly int $statusCode = 500,
        string $message = 'Unknown error.'
    ) {
        parent::__construct($message);
    }

    public static function invalidEmail(string $email): self
    {
        return new self(
            translationKey: 'error.validation.email',
            translationParams: ['email' => $email],
            statusCode: 422,
            message: sprintf('Email %s is invalid', $email)
        );
    }

    public static function userNotFoundByEmail(string $email): self
    {
        return new self(
            translationKey: 'error.user.not_found.by_email',
            translationParams: ['email' => $email],
            statusCode: 404,
            message: sprintf('User with email %s not found', $email)
        );
    }
}