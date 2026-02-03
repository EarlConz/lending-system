<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class PaymentRepository extends BaseRepository
{
  public function getDeleteStats(): array
  {
    return [
      "reversals_today" => 0,
      "pending_approval" => 0,
      "completed" => 0,
      "rejected" => 0,
    ];
  }

  public function getPostStats(): array
  {
    return [
      "payments_today" => 0,
      "cash" => 0,
      "bank_transfer" => 0,
      "auto_debit" => 0,
    ];
  }

  public function getRecentPayments(int $limit = 3): array
  {
    return [];
  }

  public function getPostedPayments(): array
  {
    return [];
  }

  public function getEditAmortizationStats(): array
  {
    return [
      "accounts_reviewed" => 0,
      "pending_edits" => 0,
      "new_schedules" => 0,
      "escalated" => 0,
    ];
  }

  public function getAmortizationSchedule(?int $loanId): array
  {
    return [];
  }
}
