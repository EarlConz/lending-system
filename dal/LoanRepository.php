<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class LoanRepository extends BaseRepository
{
  public function getPendingStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_review,
        SUM(CASE WHEN status = 'Pending' AND (collateral IS NULL OR collateral = '') THEN 1 ELSE 0 END) AS needs_documents,
        SUM(CASE WHEN status = 'Pending' AND priority = 'High' THEN 1 ELSE 0 END) AS supervisor_review,
        SUM(CASE WHEN status = 'Pending' AND submitted_date < DATE_SUB(CURDATE(), INTERVAL 14 DAY) THEN 1 ELSE 0 END) AS overdue
       FROM loan_applications"
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
      "SELECT
        SUM(CASE WHEN submitted_date = CURDATE() THEN 1 ELSE 0 END) AS applications_today,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS waiting_approval,
        SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS auto_approved,
        SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high_risk
       FROM loan_applications"
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
      "SELECT
        a.application_id,
        a.requested_amount,
        a.submitted_date,
        a.priority,
        c.first_name,
        c.last_name
       FROM loan_applications a
       LEFT JOIN clients c ON c.id = a.client_id
       WHERE a.status = 'Pending'
       ORDER BY a.submitted_date DESC"
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
      "SELECT
        SUM(CASE WHEN status = 'Ready' THEN 1 ELSE 0 END) AS ready_for_release,
        SUM(CASE WHEN release_date = CURDATE() THEN 1 ELSE 0 END) AS released_today,
        SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) AS scheduled_releases,
        SUM(CASE WHEN status = 'Hold' THEN 1 ELSE 0 END) AS on_hold
       FROM loan_releases"
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
      "SELECT
        l.loan_id,
        l.amount,
        l.term_months,
        l.approval_date,
        r.status,
        c.first_name,
        c.last_name
       FROM loans l
       LEFT JOIN loan_releases r ON r.loan_id = l.id
       LEFT JOIN clients c ON c.id = l.client_id
       WHERE l.status IN ('Active', 'Delinquent')
       ORDER BY l.approval_date DESC"
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
      "SELECT
        SUM(CASE WHEN status = 'Hold' THEN 1 ELSE 0 END) AS deletes_pending,
        SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) AS supervisor_approvals
       FROM loan_releases"
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
      "SELECT
        r.release_id,
        r.amount,
        r.release_date,
        c.first_name,
        c.last_name
       FROM loan_releases r
       LEFT JOIN loans l ON l.id = r.loan_id
       LEFT JOIN clients c ON c.id = l.client_id
       WHERE r.status = 'Hold'
       ORDER BY r.release_date DESC"
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
      "INSERT INTO loan_applications (
        application_id,
        client_id,
        requested_amount,
        monthly_income,
        employment_info,
        terms_months,
        collateral,
        guarantor,
        status,
        priority,
        submitted_date
      ) VALUES (
        :application_id,
        :client_id,
        :requested_amount,
        :monthly_income,
        :employment_info,
        :terms_months,
        :collateral,
        :guarantor,
        :status,
        :priority,
        :submitted_date
      )",
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

    $sql = "UPDATE loan_applications SET " . implode(", ", $columns) . " WHERE id = :id";
    return $this->execute($sql, $params);
  }

  public function generateApplicationId(): string
  {
    $row = $this->fetchOne(
      "SELECT MAX(CAST(SUBSTRING(application_id, 5) AS UNSIGNED)) AS max_id
       FROM loan_applications
       WHERE application_id LIKE 'APP-%'"
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("APP-%06d", $next);
  }

  public function createLoan(array $data): int
  {
    $this->execute(
      "INSERT INTO loans (
        loan_id,
        client_id,
        product_id,
        amount,
        balance,
        term_months,
        approval_date,
        status
      ) VALUES (
        :loan_id,
        :client_id,
        :product_id,
        :amount,
        :balance,
        :term_months,
        :approval_date,
        :status
      )",
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

    $sql = "UPDATE loans SET " . implode(", ", $columns) . " WHERE id = :id";
    return $this->execute($sql, $params);
  }

  public function deleteLoan(int $loanId): bool
  {
    return $this->execute(
      "DELETE FROM loans WHERE id = :id",
      [
        ":id" => $loanId,
      ]
    );
  }

  public function generateLoanId(): string
  {
    $row = $this->fetchOne(
      "SELECT MAX(CAST(SUBSTRING(loan_id, 4) AS UNSIGNED)) AS max_id
       FROM loans
       WHERE loan_id LIKE 'LN-%'"
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("LN-%06d", $next);
  }
}
