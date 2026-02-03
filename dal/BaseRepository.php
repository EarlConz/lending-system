<?php
declare(strict_types=1);

require_once __DIR__ . "/../database/Database.php";

abstract class BaseRepository
{
  protected function db(): PDO
  {
    return Database::connection();
  }

  protected function fetchAll(string $sql, array $params = []): array
  {
    $stmt = $this->db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  protected function fetchOne(string $sql, array $params = []): ?array
  {
    $stmt = $this->db()->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();
    return $row === false ? null : $row;
  }

  protected function execute(string $sql, array $params = []): bool
  {
    $stmt = $this->db()->prepare($sql);
    return $stmt->execute($params);
  }
}
