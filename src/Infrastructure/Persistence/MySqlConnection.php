<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Persistence;

use PDO;
use SensitiveParameter;
use Shepherdmat\Phinanse\Shared\Persistence\ConnectionInterface;

class MySqlConnection implements ConnectionInterface
{
    private PDO $pdo;

    public function __construct(
        string                       $databaseHost,
        int                          $databasePort,
        string                       $databaseCharset,
        string                       $databaseName,
        string                       $databaseUser,
        #[SensitiveParameter] string $databasePassword,

    )
    {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $databaseHost, $databasePort, $databaseName, $databaseCharset);

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false,
        ];

        $this->pdo = new PDO($dsn, $databaseUser, $databasePassword, $options);
    }

//    public function fetchAll(string $sql, array $params = []): array
//    {
//        $stmt = $this->pdo->prepare($sql);
//        $stmt->execute($params);
//
//        return $stmt->fetchAll();
//    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch();

        return $result === false ? null : $result;
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

//
//    public function execute(string $sql, array $params = []): int
//    {
//        $stmt = $this->pdo->prepare($sql);
//        $stmt->execute($params);
//
//        return $stmt->rowCount();
//    }
//
//    public function beginTransaction(): bool
//    {
//        return $this->pdo->beginTransaction();
//    }
//
//    public function commit(): bool
//    {
//        return $this->pdo->commit();
//    }
//
//    public function rollBack(): bool
//    {
//        return $this->pdo->rollBack();
//    }
}
