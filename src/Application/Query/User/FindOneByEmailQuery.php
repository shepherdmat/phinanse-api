<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Query\User;

use Shepherdmat\Phinanse\Shared\Messenger\QueryMessageInterface;
use Shepherdmat\Phinanse\Shared\ValueObject\Email;

final readonly class FindOneByEmailQuery implements QueryMessageInterface
{
    public function __construct(
        public Email $email,
    ) {
    }
}