<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class BackupRepository extends BaseRepository
{
  public function createBackup(string $label, ?int $createdBy): bool
  {
    return $this->execute(
      "INSERT INTO db_backups (label, created_by) VALUES (:label, :created_by)",
      [
        ":label" => $label,
        ":created_by" => $createdBy,
      ]
    );
  }

  public function getAllBackups(): array
  {
    return $this->fetchAll(
      "SELECT b.id, b.label, b.created_at, u.username AS created_by
       FROM db_backups b
       LEFT JOIN users u ON u.id = b.created_by
       ORDER BY b.created_at DESC"
    );
  }
}
