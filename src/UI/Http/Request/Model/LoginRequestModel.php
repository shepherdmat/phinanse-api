<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\UI\Http\Request\Model;

use Shepherdmat\Phinanse\Shared\ValueObject\Email;

final readonly class LoginRequestModel
{
    public function __construct(
       public Email $email,
       public string $password
    ){}
}