<?php

declare(strict_types=1);

namespace Shepherdmat\Phinanse\Infrastructure\Persistence;

use PDO;
use SensitiveParameter;

class MySqlConnection
{
    private PDO $pdo;

    public function __construct(
        string                       $host,
        int                          $port,
        string                       $charset,
        string                       $database,
        string                       $username,
        #[SensitiveParameter] string $password,

    )
    {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $database, $charset);

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false,
        ];

        $this->pdo = new PDO($dsn, $username, $password, $options);
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
