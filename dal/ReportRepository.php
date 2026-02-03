<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class ReportRepository extends BaseRepository
{
  public function getListingStats(): array
  {
    return [
      "saved_templates" => 0,
      "exports_week" => 0,
      "shared" => 0,
      "drafts" => 0,
    ];
  }

  public function getSavedListings(): array
  {
    return [];
  }

  public function getLoanListingStats(): array
  {
    return [
      "total_loans" => 0,
      "active" => 0,
      "closed" => 0,
      "delinquent" => 0,
    ];
  }

  public function getLoanListing(): array
  {
    return [];
  }

  public function getLoanPaymentStats(): array
  {
    return [
      "payments" => 0,
      "on_time_rate" => "0%",
      "delinquent" => 0,
      "defaults" => 0,
    ];
  }

  public function getLoanPayments(): array
  {
    return [];
  }

  public function getLoanReleaseStats(): array
  {
    return [
      "releases" => 0,
      "total_value" => "0",
      "branches" => 0,
      "products" => 0,
    ];
  }

  public function getLoanReleases(): array
  {
    return [];
  }

  public function getPaidLoanStats(): array
  {
    return [
      "paid_year" => 0,
      "renewed" => 0,
      "disputed" => 0,
      "write_offs" => 0,
    ];
  }

  public function getPaidLoans(): array
  {
    return [];
  }

  public function getTransactionStats(): array
  {
    return [
      "transactions" => 0,
      "payments" => 0,
      "releases" => 0,
      "adjustments" => 0,
    ];
  }

  public function getTransactionSummary(): array
  {
    return [];
  }
}
