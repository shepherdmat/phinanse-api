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
        // Dodano password_hash do SELECTa
        $sql = 'SELECT id, email, password_hash, created_at FROM users WHERE id = :id LIMIT 1';

        $result = $this->connection->fetch($sql, [
            'id' => $id->toBinary() // Poprawka: szukamy po surowych bajtach, a nie po stringu!
        ]);

        if (!$result) {
            return null;
        }

        return UserMapper::createFromDatabase($result);
    }

    public function save(User $user): void
    {
        $sql = 'INSERT INTO users (id, email, password_hash, created_at) 
                VALUES (:id, :email, :password_hash, :created_at)';

        // Używamy mappera, aby wstrzyknąć gotową tablicę asocjacyjną
        $this->connection->execute($sql, UserMapper::toDatabase($user));
    }
}