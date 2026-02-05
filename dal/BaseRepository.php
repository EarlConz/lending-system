<?php
declare(strict_types=1);

require_once __DIR__ . "/../database/Database.php";
require_once __DIR__ . "/SqlQueries.php";

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

  public function withTransaction(callable $callback)
  {
    $db = $this->db();
    try {
      $db->beginTransaction();
      $result = $callback();
      $db->commit();
      return $result;
    } catch (Throwable $exception) {
      if ($db->inTransaction()) {
        $db->rollBack();
      }
      throw $exception;
    }
  }
}
