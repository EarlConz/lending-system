<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class BranchRepository extends BaseRepository
{
  public function getAllBranches(): array
  {
    return $this->fetchAll(
      SqlQueries::get("branch.all")
    );
  }
}
