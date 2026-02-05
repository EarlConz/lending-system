<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class BackupRepository extends BaseRepository
{
  public function createBackup(string $label, ?int $createdBy): bool
  {
    return $this->execute(
      SqlQueries::get("backup.create"),
      [
        ":label" => $label,
        ":created_by" => $createdBy,
      ]
    );
  }

  public function getAllBackups(): array
  {
    return $this->fetchAll(
      SqlQueries::get("backup.list")
    );
  }
}
