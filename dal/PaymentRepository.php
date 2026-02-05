<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class PaymentRepository extends BaseRepository
{
  public function getDeleteStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("payment.delete_stats")
    ) ?? [];

    return [
      "reversals_today" => (int) ($row["reversals_today"] ?? 0),
      "pending_approval" => (int) ($row["pending_approval"] ?? 0),
      "completed" => (int) ($row["completed"] ?? 0),
      "rejected" => (int) ($row["rejected"] ?? 0),
    ];
  }

  public function getPostStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("payment.post_stats")
    ) ?? [];

    return [
      "payments_today" => (int) ($row["payments_today"] ?? 0),
      "cash" => (int) ($row["cash"] ?? 0),
      "bank_transfer" => (int) ($row["bank_transfer"] ?? 0),
      "auto_debit" => (int) ($row["auto_debit"] ?? 0),
    ];
  }

  public function getRecentPayments(int $limit = 3): array
  {
    $limit = max(1, $limit);
    $rows = $this->fetchAll(
      sprintf(SqlQueries::get("payment.recent"), $limit)
    );

    $payments = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?? "Posted";
      $statusClass = $status === "Posted" ? "ok" : "warn";
      $label = $row["payment_id"];
      $borrower = trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? ""));
      if ($borrower !== "") {
        $label .= " • " . $borrower;
      }

      $payments[] = [
        "label" => $label,
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $payments;
  }

  public function getPostedPayments(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("payment.posted")
    );

    $payments = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?? "Posted";
      $statusClass = $status === "Posted" ? "ok" : "warn";
      $payments[] = [
        "payment_id" => $row["payment_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "date" => $row["payment_date"],
        "method" => $row["method"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $payments;
  }

  public function getEditAmortizationStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("payment.edit_amort_stats")
    ) ?? [];

    return [
      "accounts_reviewed" => (int) ($row["accounts_reviewed"] ?? 0),
      "pending_edits" => 0,
      "new_schedules" => (int) ($row["new_schedules"] ?? 0),
      "escalated" => 0,
    ];
  }

  public function getAmortizationSchedule(?int $loanId): array
  {
    if ($loanId === null) {
      return [];
    }

    return $this->fetchAll(
      SqlQueries::get("payment.amortization_schedule"),
      [
        ":loan_id" => $loanId,
      ]
    );
  }
}
