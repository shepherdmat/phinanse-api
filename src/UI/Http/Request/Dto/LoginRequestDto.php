<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Request\Dto;

use Shepherdmat\Phinanse\Shared\ValueObject\Email;

final readonly class LoginRequestDto
{
    public function __construct(
       public Email $email,
       public string $password
    ){}
}