<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Application\Exception;

use RuntimeException;
use Shepherdmat\Phinanse\Shared\Exception\NotFoundExceptionInterface;

final class NotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
}