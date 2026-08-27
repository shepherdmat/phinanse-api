<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Persistence\Repository;

use DateMalformedStringException;
use Shepherdmat\Phinanse\Domain\Entity\User;
use Shepherdmat\Phinanse\Domain\Repository\UserRepositoryInterface;
use Shepherdmat\Phinanse\Infrastructure\Persistence\Mapper\UserMapper;
use Shepherdmat\Phinanse\Infrastructure\Persistence\MySqlConnection;
use Shepherdmat\Phinanse\Shared\ValueObject\Uuid;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(private MySqlConnection $connection)
    {
    }

    /**
     * @throws DateMalformedStringException
     */
    public function findOneById(Uuid $id): ?User
    {
        $sql = 'SELECT id, email, created_at FROM users WHERE id = :id LIMIT 1';

        $result = $this->connection->fetch($sql, [
            'id' => $id->toString()
        ]);

        if (!$result) {
            return null;
        }

        return UserMapper::createFromDatabase($result);
    }
}