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
        "id" => (int) ($row["id"] ?? 0),
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

  public function getLoanApplicationById(int $applicationId): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("loan.application_by_id"),
      [
        ":id" => $applicationId,
      ]
    );
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

  public function getApprovedUnreleasedLoans(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.approved_unreleased_list")
    );

    $loans = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?: "Active";
      $statusClass = $status === "Active" ? "ok" : "warn";
      $loans[] = [
        "source" => "Loan",
        "is_selectable" => true,
        "loan_pk" => (int) ($row["loan_pk"] ?? 0),
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "product" => $row["product_name"],
        "amount" => $row["amount"],
        "balance" => $row["balance"],
        "term" => $row["term_months"] ? $row["term_months"] . " months" : "",
        "approval_date" => $row["approval_date"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $loans;
  }

  public function getApprovedApplications(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.approved_applications_list")
    );

    $applications = [];
    foreach ($rows as $row) {
      $applications[] = [
        "source" => "Application",
        "is_selectable" => false,
        "application_pk" => (int) ($row["application_pk"] ?? 0),
        "application_id" => $row["application_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "product" => $row["product_name"],
        "amount" => $row["requested_amount"],
        "term" => $row["terms_months"] ? $row["terms_months"] . " months" : "",
        "approval_date" => $row["submitted_date"],
        "status_label" => "Approved",
        "status_class" => "ok",
      ];
    }

    return $applications;
  }

  public function getReleasedApplications(): array
  {
    $rows = $this->fetchAll(
      SqlQueries::get("loan.released_list")
    );

    $releases = [];
    foreach ($rows as $row) {
      $status = $row["status"] ?: "Ready";
      $statusClass = $status === "Released" ? "ok" : "warn";
      $releases[] = [
        "release_pk" => (int) ($row["release_pk"] ?? 0),
        "release_id" => $row["release_id"],
        "loan_pk" => (int) ($row["loan_pk"] ?? 0),
        "loan_id" => $row["loan_id"],
        "borrower" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "amount" => $row["amount"],
        "term" => $row["term_months"] ? $row["term_months"] . " months" : "",
        "approval_date" => $row["approval_date"],
        "release_date" => $row["release_date"],
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $releases;
  }

  public function getReleaseCandidates(): array
  {
    $rows = $this->fetchAll(SqlQueries::get("loan.release_candidates"));

    $candidates = [];
    foreach ($rows as $row) {
      $lastName = trim((string) ($row["last_name"] ?? ""));
      $firstName = trim((string) ($row["first_name"] ?? ""));
      $clientName = $lastName !== "" && $firstName !== ""
        ? $lastName . ", " . $firstName
        : trim($lastName . $firstName);
      $termMonths = $row["term_months"] ?? null;
      $termLabel = $termMonths ? $termMonths . " months" : "";
      $candidates[] = [
        "loan_pk" => (int) ($row["loan_pk"] ?? 0),
        "loan_id" => $row["loan_id"],
        "client_name" => $clientName,
        "product" => $row["product_name"],
        "current_balance" => $row["balance"],
        "approval_date" => $row["approval_date"],
        "initial_term" => $termLabel,
        "initial_amount" => $row["amount"],
        "final_term" => $termLabel,
        "final_amount" => $row["amount"],
        "interest_rate" => $row["interest_rate"],
      ];
    }

    return $candidates;
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
        "release_pk" => (int) ($row["release_pk"] ?? 0),
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

  public function getLoanApplicationViewById(int $applicationId): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("loan.application_view_by_id"),
      [
        ":id" => $applicationId,
      ]
    );
  }

  public function getLoanById(int $loanId): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("loan.by_id"),
      [
        ":id" => $loanId,
      ]
    );
  }

  public function getReleaseById(int $releaseId): ?array
  {
    return $this->fetchOne(
      SqlQueries::get("loan.release_by_id"),
      [
        ":id" => $releaseId,
      ]
    );
  }

  public function createLoanApplication(array $data): int
  {
    $schedules = $data["schedules"] ?? [];

    return (int) $this->withTransaction(function () use ($data, $schedules) {
      $this->execute(
        SqlQueries::get("loan.application_insert"),
        [
          ":application_id" => $data["application_id"],
          ":client_id" => $data["client_id"],
          ":product_id" => $data["product_id"],
          ":requested_amount" => $data["requested_amount"],
          ":monthly_income" => $data["monthly_income"],
          ":employment_info" => $data["employment_info"],
          ":terms_months" => $data["terms_months"],
          ":term_unit" => $data["term_unit"],
          ":term_fixed" => $data["term_fixed"],
          ":savings_account" => $data["savings_account"],
          ":collateral" => $data["collateral"],
          ":guarantor" => $data["guarantor"],
          ":interest_rate" => $data["interest_rate"],
          ":interest_type" => $data["interest_type"],
          ":equal_principal" => $data["equal_principal"],
          ":release_date" => $data["release_date"],
          ":maturity_date" => $data["maturity_date"],
          ":deduction_interest" => $data["deduction_interest"],
          ":deduction_service_charge" => $data["deduction_service_charge"],
          ":deduction_climbs" => $data["deduction_climbs"],
          ":deduction_notarial_fee" => $data["deduction_notarial_fee"],
          ":total_deductions" => $data["total_deductions"],
          ":net_proceeds" => $data["net_proceeds"],
          ":amortization_days" => $data["amortization_days"],
          ":principal_interval" => $data["principal_interval"],
          ":interval_adjustment" => $data["interval_adjustment"],
          ":fixed_amortization" => $data["fixed_amortization"],
          ":irregular_amortization" => $data["irregular_amortization"],
          ":insurance_amount" => $data["insurance_amount"],
          ":insurance_basis" => $data["insurance_basis"],
          ":interest_amortized" => $data["interest_amortized"],
          ":service_charge_amortized" => $data["service_charge_amortized"],
          ":client_photo_path" => $data["client_photo_path"],
          ":status" => $data["status"],
          ":priority" => $data["priority"],
          ":submitted_date" => $data["submitted_date"],
        ]
      );

      $applicationId = (int) $this->db()->lastInsertId();

      if (!empty($schedules)) {
        $this->createLoanApplicationSchedules($applicationId, $schedules);
      }

      return $applicationId;
    });
  }

  public function createLoanApplicationSchedules(int $applicationId, array $schedules): void
  {
    foreach ($schedules as $schedule) {
      $this->execute(
        SqlQueries::get("loan.application_schedule_insert"),
        [
          ":loan_application_id" => $applicationId,
          ":installment_no" => $schedule["installment_no"],
          ":due_date" => $schedule["due_date"],
          ":principal" => $schedule["principal"],
          ":interest" => $schedule["interest"],
          ":total" => $schedule["total"],
          ":balance" => $schedule["balance"],
        ]
      );
    }
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

  public function generateReleaseId(): string
  {
    $row = $this->fetchOne(
      SqlQueries::get("loan.release_max_id")
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("RL-%06d", $next);
  }

  public function createRelease(array $data): void
  {
    $this->execute(
      SqlQueries::get("loan.release_insert"),
      [
        ":release_id" => $data["release_id"],
        ":loan_id" => $data["loan_id"],
        ":amount" => $data["amount"],
        ":release_date" => $data["release_date"],
        ":status" => $data["status"],
      ]
    );
  }
}
