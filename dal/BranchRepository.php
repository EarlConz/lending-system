<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class BranchRepository extends BaseRepository
{
  public function getAllBranches(): array
  {
    return $this->fetchAll(
      "SELECT id, code, name
       FROM branches
       ORDER BY name ASC"
    );
  }
}
