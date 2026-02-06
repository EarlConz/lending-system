<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class CacobemRepository extends BaseRepository
{
  public function create(array $data): int
  {
    $this->execute(
      SqlQueries::get("cacobem.insert"),
      [
        ":client_id" => $data["client_id"],
        ":borrower_name" => $data["borrower_name"],
        ":application_date" => $data["application_date"],
        ":amount_applied" => $data["amount_applied"],
        ":data_json" => $data["data_json"],
      ]
    );

    return (int) $this->db()->lastInsertId();
  }

  public function update(int $id, array $data): bool
  {
    return $this->execute(
      SqlQueries::get("cacobem.update"),
      [
        ":id" => $id,
        ":client_id" => $data["client_id"],
        ":borrower_name" => $data["borrower_name"],
        ":application_date" => $data["application_date"],
        ":amount_applied" => $data["amount_applied"],
        ":data_json" => $data["data_json"],
      ]
    );
  }

  public function findById(int $id): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("cacobem.by_id"),
      [
        ":id" => $id,
      ]
    );
  }

  public function listAll(): array
  {
    return $this->fetchAll(
      SqlQueries::get("cacobem.list")
    );
  }
}
