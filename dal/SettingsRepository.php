<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class SettingsRepository extends BaseRepository
{
  public function getProductStats(): array
  {
    return [
      "active" => 0,
      "pending_updates" => 0,
      "draft" => 0,
      "archived" => 0,
    ];
  }

  public function getLoanProducts(): array
  {
    return [];
  }

  public function getRecommendedProducts(int $limit = 2): array
  {
    return [];
  }
}
