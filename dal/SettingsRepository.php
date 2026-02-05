<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class SettingsRepository extends BaseRepository
{
  public function getProductStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("settings.product_stats")
    ) ?? [];

    return [
      "active" => (int) ($row["active"] ?? 0),
      "pending_updates" => 0,
      "draft" => 0,
      "archived" => (int) ($row["archived"] ?? 0),
    ];
  }

  public function getLoanProducts(?string $status = null): array
  {
    if ($status === "Active" || $status === "Inactive") {
      return $this->fetchAll(
        SqlQueries::get("settings.loan_products_by_status"),
        [
          ":status" => $status,
        ]
      );
    }

    return $this->fetchAll(SqlQueries::get("settings.loan_products_all"));
  }

  public function getLoanProductById(int $id): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("settings.loan_product_by_id"),
      [$id]
    );
  }

  public function createLoanProduct(array $payload): int
  {
    $this->execute(
      SqlQueries::get("settings.loan_product_insert"),
      $payload
    );

    return (int) $this->db()->lastInsertId();
  }

  public function updateLoanProduct(int $id, array $payload): void
  {
    $payload["id"] = $id;
    $this->execute(
      SqlQueries::get("settings.loan_product_update"),
      $payload
    );
  }

  public function getRecommendedProducts(int $limit = 2): array
  {
    $limit = max(1, $limit);
    return $this->fetchAll(
      sprintf(SqlQueries::get("settings.recommended_products"), $limit)
    );
  }
}
