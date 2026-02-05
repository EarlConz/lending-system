<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class LoanRepository extends BaseRepository
{
  public function getPendingStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.pending_stats")
    ) ?? [];

    return [
      "pending_review" => (int) ($row["pending_review"] ?? 0),
      "needs_documents" => (int) ($row["needs_documents"] ?? 0),
      "supervisor_review" => (int) ($row["supervisor_review"] ?? 0),
      "overdue" => (int) ($row["overdue"] ?? 0),
    ];
  }

  public function getApplicationStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.application_stats")
    ) ?? [];

    return [
      "applications_today" => (int) ($row["applications_today"] ?? 0),
      "waiting_approval" => (int) ($row["waiting_approval"] ?? 0),
      "auto_approved" => (int) ($row["auto_approved"] ?? 0),
      "high_risk" => (int) ($row["high_risk"] ?? 0),
    ];
  }

  public function getPendingApplications(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.pending_list")
    );

    $applications = [];
    foreach ($rows as $row) {
      $priority = $row["priority"] ?? "Normal";
      $priorityClass = $priority === "High" ? "warn" : ($priority === "Normal" ? "ok" : "");
      $applications[] = [
        "application_id" => $row["application_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "requested_amount" => $row["requested_amount"],
        "submitted_date" => $row["submitted_date"],
        "priority_label" => $priority,
        "priority_class" => $priorityClass,
      ];
    }

    return $applications;
  }

  public function getReleaseStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.release_stats")
    ) ?? [];

    return [
      "ready_for_release" => (int) ($row["ready_for_release"] ?? 0),
      "released_today" => (int) ($row["released_today"] ?? 0),
      "scheduled_releases" => (int) ($row["scheduled_releases"] ?? 0),
      "on_hold" => (int) ($row["on_hold"] ?? 0),
    ];
  }

  public function getApprovedReleases(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.approved_releases")
    );

    $releases = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?: "Ready";
      $statusClass = $status === "Ready" ? "ok" : "warn";
      $releases[] = [
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "term" => $row["term_months"] ? $row["term_months"] . " months" : "",
        "approval_date" => $row["approval_date"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $releases;
  }

  public function getReleaseDeletionStats(): array
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.release_deletion_stats")
    ) ?? [];

    return [
      "deletes_pending" => (int) ($row["deletes_pending"] ?? 0),
      "supervisor_approvals" => (int) ($row["supervisor_approvals"] ?? 0),
      "flagged_issues" => 0,
      "blocked" => 0,
    ];
  }

  public function getReleaseDeletions(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.release_deletions")
    );

    $deletions = [];
    foreach ($rows as $row) {
      $deletions[] = [
        "release_id" => $row["release_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "released_date" => $row["release_date"],
        "reason" => "Pending review",
        "status_label" => "On hold",
        "status_class" => "warn",
      ];
    }

    return $deletions;
  }

  public function createLoanApplication(array $data): int
  {
    $this->execute(
      SqlQueries::get("loan.application_insert"),
      [
        ":application_id" => $data["application_id"],
        ":client_id" => $data["client_id"],
        ":requested_amount" => $data["requested_amount"],
        ":monthly_income" => $data["monthly_income"],
        ":employment_info" => $data["employment_info"],
        ":terms_months" => $data["terms_months"],
        ":collateral" => $data["collateral"],
        ":guarantor" => $data["guarantor"],
        ":status" => $data["status"],
        ":priority" => $data["priority"],
        ":submitted_date" => $data["submitted_date"],
      ]
    );

    return (int) $this->db()->lastInsertId();
  }

  public function updateLoanApplication(int $applicationId, array $data): bool
  {
    if (empty($data)) {
      return false;
    }

    $columns = [];
    $params = [":id" => $applicationId];

    foreach ($data as $key => $value) {
      $columns[] = "{$key} = :{$key}";
      $params[":{$key}"] = $value;
    }

    $sql = sprintf(SqlQueries::get("loan.application_update"), implode(", ", $columns));
    return $this->execute($sql, $params);
  }

  public function generateApplicationId(): string
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.application_max_id")
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("APP-%06d", $next);
  }

  public function createLoan(array $data): int
  {
    $this->execute(
      SqlQueries::get("loan.insert"),
      [
        ":loan_id" => $data["loan_id"],
        ":client_id" => $data["client_id"],
        ":product_id" => $data["product_id"],
        ":amount" => $data["amount"],
        ":balance" => $data["balance"],
        ":term_months" => $data["term_months"],
        ":approval_date" => $data["approval_date"],
        ":status" => $data["status"],
      ]
    );

    return (int) $this->db()->lastInsertId();
  }

  public function updateLoan(int $loanId, array $data): bool
  {
    if (empty($data)) {
      return false;
    }

    $columns = [];
    $params = [":id" => $loanId];

    foreach ($data as $key => $value) {
      $columns[] = "{$key} = :{$key}";
      $params[":{$key}"] = $value;
    }

    $sql = sprintf(SqlQueries::get("loan.update"), implode(", ", $columns));
    return $this->execute($sql, $params);
  }

  public function deleteLoan(int $loanId): bool
  {
    return $this->execute(
      SqlQueries::get("loan.delete"),
      [
        ":id" => $loanId,
      ]
    );
  }

  public function generateLoanId(): string
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.max_id")
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("LN-%06d", $next);
  }
}
