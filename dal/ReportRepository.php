<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class ReportRepository extends BaseRepository
{
  public function getListingStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("report.listing_stats")
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
      SqlQueries::get("report.saved_listings")
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
      SqlQueries::get("report.loan_listing_stats")
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
      SqlQueries::get("report.loan_listing")
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
      SqlQueries::get("report.loan_payment_stats")
    ) ?? [];

    $total = (int) ($row["payments"] ?? 0);
    $posted = (int) ($row["posted"] ?? 0);
    $onTimeRate = $total > 0 ? round(($posted / $total) * 100) . "%" : "0%";

    $delinquent = $this->fetchOne(
      SqlQueries::get("report.delinquent_count")
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
      SqlQueries::get("report.loan_payments")
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
      SqlQueries::get("report.loan_release_stats")
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
      SqlQueries::get("report.loan_releases")
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
      SqlQueries::get("report.paid_loan_stats")
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
      SqlQueries::get("report.paid_loans")
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
      SqlQueries::get("report.transaction_payments")
    ) ?? [];
    $releasesRow = $this->fetchOne(
      SqlQueries::get("report.transaction_releases")
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
      SqlQueries::get("report.transaction_summary")
    );

    return $rows;
  }
}
