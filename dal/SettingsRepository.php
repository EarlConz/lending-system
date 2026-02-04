<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class SettingsRepository extends BaseRepository
{
  public function getProductStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) AS archived
       FROM loan_products"
    ) ?? [];

    return [
      "active" => (int) ($row["active"] ?? 0),
      "pending_updates" => 0,
      "draft" => 0,
      "archived" => (int) ($row["archived"] ?? 0),
    ];
  }

  public function getLoanProducts(): array
  {
    return $this->fetchAll(
      "SELECT id, name, interest_rate, service_charge, status
       FROM loan_products
       ORDER BY created_at DESC"
    );
  }

  public function getRecommendedProducts(int $limit = 2): array
  {
    $limit = max(1, $limit);
    return $this->fetchAll(
      "SELECT id, name, interest_rate, service_charge, status
       FROM loan_products
       WHERE status = 'Active'
       ORDER BY created_at DESC
       LIMIT {$limit}"
    );
  }
}
