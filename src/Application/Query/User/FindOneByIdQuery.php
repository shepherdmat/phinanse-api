<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Query\User;

use Shepherdmat\Phinanse\Shared\Messenger\QueryMessageInterface;

final readonly class FindOneByIdQuery implements QueryMessageInterface
{
    public function __construct(
        public string $id,
    )
    {

    }
}