<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class ReportRepository extends BaseRepository
{
  public function getListingStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        COUNT(*) AS saved_templates,
        SUM(CASE WHEN created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS exports_week,
        SUM(CASE WHEN status = 'Shared' THEN 1 ELSE 0 END) AS shared,
        SUM(CASE WHEN status = 'Private' THEN 1 ELSE 0 END) AS drafts
       FROM reports_saved_listings"
    ) ?? [];

    return [
      "saved_templates" => (int) ($row["saved_templates"] ?? 0),
      "exports_week" => (int) ($row["exports_week"] ?? 0),
      "shared" => (int) ($row["shared"] ?? 0),
      "drafts" => (int) ($row["drafts"] ?? 0),
    ];
  }

  public function getSavedListings(): array
  {
    $rows = $this->fetchAll(
      "SELECT name, status
       FROM reports_saved_listings
       ORDER BY created_at DESC"
    );

    $listings = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?? "Private";
      $statusClass = $status === "Shared" ? "ok" : ($status === "Scheduled" ? "warn" : "");
      $listings[] = [
        "name" => $row["name"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $listings;
  }

  public function getLoanListingStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        COUNT(*) AS total_loans,
        SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) AS closed,
        SUM(CASE WHEN status = 'Delinquent' THEN 1 ELSE 0 END) AS delinquent
       FROM loans"
    ) ?? [];

    return [
      "total_loans" => (int) ($row["total_loans"] ?? 0),
      "active" => (int) ($row["active"] ?? 0),
      "closed" => (int) ($row["closed"] ?? 0),
      "delinquent" => (int) ($row["delinquent"] ?? 0),
    ];
  }

  public function getLoanListing(): array
  {
    $rows = $this->fetchAll(
      "SELECT
        l.loan_id,
        l.amount,
        l.balance,
        l.status,
        c.first_name,
        c.last_name
       FROM loans l
       LEFT JOIN clients c ON c.id = l.client_id
       ORDER BY l.created_at DESC"
    );

    $loans = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?? "Active";
      $statusClass = $status === "Active" ? "ok" : ($status === "Delinquent" ? "warn" : "");
      $loans[] = [
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "balance" => $row["balance"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $loans;
  }

  public function getLoanPaymentStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        COUNT(*) AS payments,
        SUM(CASE WHEN status = 'Posted' THEN 1 ELSE 0 END) AS posted
       FROM payments"
    ) ?? [];

    $total = (int) ($row["payments"] ?? 0);
    $posted = (int) ($row["posted"] ?? 0);
    $onTimeRate = $total > 0 ? round(($posted / $total) * 100) . "%" : "0%";

    $delinquent = $this->fetchOne(
      "SELECT SUM(CASE WHEN status = 'Delinquent' THEN 1 ELSE 0 END) AS delinquent
       FROM loans"
    );

    return [
      "payments" => $total,
      "on_time_rate" => $onTimeRate,
      "delinquent" => (int) (($delinquent["delinquent"] ?? 0)),
      "defaults" => (int) (($delinquent["delinquent"] ?? 0)),
    ];
  }

  public function getLoanPayments(): array
  {
    $rows = $this->fetchAll(
      "SELECT
        l.loan_id,
        c.first_name,
        c.last_name,
        a.total AS amount,
        a.due_date,
        IFNULL(SUM(p.amount), 0) AS paid_amount
       FROM amortizations a
       LEFT JOIN loans l ON l.id = a.loan_id
       LEFT JOIN clients c ON c.id = l.client_id
       LEFT JOIN payments p ON p.loan_id = l.id
       GROUP BY a.id, l.loan_id, c.first_name, c.last_name, a.total, a.due_date
       ORDER BY a.due_date DESC"
    );

    $payments = [];
    foreach ($rows as $row) {
      $status = ((float) $row["paid_amount"] >= (float) $row["amount"]) ? "Paid" : "Pending";
      $statusClass = $status === "Paid" ? "ok" : "warn";
      $payments[] = [
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "due_date" => $row["due_date"],
        "paid_amount" => $row["paid_amount"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $payments;
  }

  public function getLoanReleaseStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        COUNT(*) AS releases,
        IFNULL(SUM(r.amount), 0) AS total_value,
        COUNT(DISTINCT c.branch_id) AS branches,
        COUNT(DISTINCT l.product_id) AS products
       FROM loan_releases r
       LEFT JOIN loans l ON l.id = r.loan_id
       LEFT JOIN clients c ON c.id = l.client_id"
    ) ?? [];

    return [
      "releases" => (int) ($row["releases"] ?? 0),
      "total_value" => (string) ($row["total_value"] ?? "0"),
      "branches" => (int) ($row["branches"] ?? 0),
      "products" => (int) ($row["products"] ?? 0),
    ];
  }

  public function getLoanReleases(): array
  {
    $rows = $this->fetchAll(
      "SELECT
        r.release_id,
        r.amount,
        r.release_date,
        p.name AS product,
        c.first_name,
        c.last_name
       FROM loan_releases r
       LEFT JOIN loans l ON l.id = r.loan_id
       LEFT JOIN loan_products p ON p.id = l.product_id
       LEFT JOIN clients c ON c.id = l.client_id
       ORDER BY r.release_date DESC"
    );

    $releases = [];
    foreach ($rows as $row) {
      $releases[] = [
        "release_id" => $row["release_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "product" => $row["product"],
        "release_date" => $row["release_date"],
      ];
    }

    return $releases;
  }

  public function getPaidLoanStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        SUM(CASE WHEN status = 'Closed' AND YEAR(approval_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS paid_year
       FROM loans"
    ) ?? [];

    return [
      "paid_year" => (int) ($row["paid_year"] ?? 0),
      "renewed" => 0,
      "disputed" => 0,
      "write_offs" => 0,
    ];
  }

  public function getPaidLoans(): array
  {
    $rows = $this->fetchAll(
      "SELECT
        l.loan_id,
        l.amount,
        l.approval_date,
        c.first_name,
        c.last_name
       FROM loans l
       LEFT JOIN clients c ON c.id = l.client_id
       WHERE l.status = 'Closed'
       ORDER BY l.approval_date DESC"
    );

    $loans = [];
    foreach ($rows as $row) {
      $loans[] = [
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "paid_date" => $row["approval_date"],
        "renewal" => "No",
      ];
    }

    return $loans;
  }

  public function getTransactionStats(): array
  {
    $paymentsRow = $this->fetchOne(
      "SELECT COUNT(*) AS payments, IFNULL(SUM(amount), 0) AS total
       FROM payments
       WHERE payment_date = CURDATE()"
    ) ?? [];
    $releasesRow = $this->fetchOne(
      "SELECT COUNT(*) AS releases, IFNULL(SUM(amount), 0) AS total
       FROM loan_releases
       WHERE release_date = CURDATE()"
    ) ?? [];

    $payments = (int) ($paymentsRow["payments"] ?? 0);
    $releases = (int) ($releasesRow["releases"] ?? 0);

    return [
      "transactions" => $payments + $releases,
      "payments" => $payments,
      "releases" => $releases,
      "adjustments" => 0,
    ];
  }

  public function getTransactionSummary(): array
  {
    $rows = $this->fetchAll(
      "SELECT 'Payments' AS type,
              COUNT(*) AS count,
              IFNULL(SUM(amount), 0) AS total
       FROM payments
       WHERE payment_date = CURDATE()
       UNION ALL
       SELECT 'Releases' AS type,
              COUNT(*) AS count,
              IFNULL(SUM(amount), 0) AS total
       FROM loan_releases
       WHERE release_date = CURDATE()"
    );

    return $rows;
  }
}
