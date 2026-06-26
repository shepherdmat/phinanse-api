<?php

declare(strict_types=1);

use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQuery;
use Shepherdmat\Phinanse\Application\Query\User\FindOneByIdQueryHandler;

return [
    'routing' => [
        FindOneByIdQuery::class => FindOneByIdQueryHandler::class,
    ],
];
