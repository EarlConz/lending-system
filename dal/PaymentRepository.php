<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class PaymentRepository extends BaseRepository
{
  public function getDeleteStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        SUM(CASE WHEN status = 'Reversed' AND payment_date = CURDATE() THEN 1 ELSE 0 END) AS reversals_today,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_approval,
        SUM(CASE WHEN status = 'Posted' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN status = 'Reversed' AND payment_date < CURDATE() THEN 1 ELSE 0 END) AS rejected
       FROM payments"
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
      "SELECT
        SUM(CASE WHEN payment_date = CURDATE() THEN 1 ELSE 0 END) AS payments_today,
        SUM(CASE WHEN method = 'Cash' THEN 1 ELSE 0 END) AS cash,
        SUM(CASE WHEN method = 'Bank Transfer' THEN 1 ELSE 0 END) AS bank_transfer,
        SUM(CASE WHEN method = 'Auto Debit' THEN 1 ELSE 0 END) AS auto_debit
       FROM payments"
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
      "SELECT
        p.payment_id,
        p.status,
        c.first_name,
        c.last_name
       FROM payments p
       LEFT JOIN loans l ON l.id = p.loan_id
       LEFT JOIN clients c ON c.id = l.client_id
       ORDER BY p.created_at DESC
       LIMIT {$limit}"
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
      "SELECT
        p.payment_id,
        p.amount,
        p.payment_date,
        p.method,
        p.status,
        c.first_name,
        c.last_name
       FROM payments p
       LEFT JOIN loans l ON l.id = p.loan_id
       LEFT JOIN clients c ON c.id = l.client_id
       WHERE p.status = 'Posted'
       ORDER BY p.payment_date DESC"
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
      "SELECT
        COUNT(DISTINCT loan_id) AS accounts_reviewed,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS new_schedules
       FROM amortizations"
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
      "SELECT due_date, principal, interest, penalty, total, note
       FROM amortizations
       WHERE loan_id = :loan_id
       ORDER BY due_date ASC",
      [
        ":loan_id" => $loanId,
      ]
    );
  }
}
