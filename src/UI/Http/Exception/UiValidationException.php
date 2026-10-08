<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Exception;

use Exception;

final class UiValidationException extends Exception
{
    public function __construct(
        public readonly array $errors,
        string $message = 'Form validation failed.',
        int $code = 422
    ) {
        parent::__construct($message, $code);
    }

    public static function withErrors(array $errors): self
    {
        return new self(errors: $errors);
    }
}