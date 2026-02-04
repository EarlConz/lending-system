<?php
declare(strict_types=1);

require_once __DIR__ . "/BaseRepository.php";

class ClientRepository extends BaseRepository
{
  public function getDashboardStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        COUNT(*) AS active,
        SUM(CASE WHEN verification_status = 'Needs Follow-up' THEN 1 ELSE 0 END) AS pending_verification,
        SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS new_applications,
        SUM(CASE WHEN risk_category IN ('PEP', 'DOSRI', 'RPT') THEN 1 ELSE 0 END) AS high_risk
       FROM clients"
    ) ?? [];

    return [
      "active" => (int) ($row["active"] ?? 0),
      "pending_verification" => (int) ($row["pending_verification"] ?? 0),
      "new_applications" => (int) ($row["new_applications"] ?? 0),
      "high_risk" => (int) ($row["high_risk"] ?? 0),
    ];
  }

  public function getRecentClients(int $limit = 4): array
  {
    $limit = max(1, $limit);
    $rows = $this->fetchAll(
      "SELECT id, first_name, middle_name, last_name
       FROM clients
       ORDER BY created_at DESC
       LIMIT {$limit}"
    );

    $clients = [];
    foreach ($rows as $row) {
      $nameParts = array_filter([
        $row["last_name"] ?? "",
        $row["first_name"] ?? "",
      ]);
      $name = trim(implode(", ", $nameParts));
      if (!empty($row["middle_name"])) {
        $name .= " " . $row["middle_name"];
      }
      $clients[] = [
        "name" => $name,
        "edit_url" => "client-edit.php?id=" . (int) $row["id"],
      ];
    }

    return $clients;
  }

  public function getEditStats(): array
  {
    $row = $this->fetchOne(
      "SELECT
        SUM(CASE WHEN last_review_date = CURDATE() THEN 1 ELSE 0 END) AS edits_today,
        SUM(CASE WHEN verification_status = 'Needs Follow-up' THEN 1 ELSE 0 END) AS pending_review,
        SUM(CASE WHEN secondary_id IS NOT NULL AND secondary_id <> '' THEN 1 ELSE 0 END) AS id_updates,
        SUM(CASE WHEN risk_category IN ('PEP', 'DOSRI', 'RPT') THEN 1 ELSE 0 END) AS risk_escalations
       FROM clients"
    ) ?? [];

    return [
      "edits_today" => (int) ($row["edits_today"] ?? 0),
      "pending_review" => (int) ($row["pending_review"] ?? 0),
      "id_updates" => (int) ($row["id_updates"] ?? 0),
      "risk_escalations" => (int) ($row["risk_escalations"] ?? 0),
    ];
  }

  public function getClientsNeedingUpdates(int $limit = 4): array
  {
    $limit = max(1, $limit);
    $rows = $this->fetchAll(
      "SELECT id, first_name, last_name, verification_status, last_review_date
       FROM clients
       ORDER BY
         CASE WHEN last_review_date IS NULL THEN 0 ELSE 1 END,
         last_review_date ASC,
         created_at DESC
       LIMIT {$limit}"
    );

    $clients = [];
    foreach ($rows as $row) {
      $status = $row["verification_status"] ?? "Needs Follow-up";
      $statusClass = $status === "Verified" ? "ok" : "warn";
      $clients[] = [
        "name" => trim(($row["first_name"] ?? "") . " " . ($row["last_name"] ?? "")),
        "status_label" => $status,
        "status_class" => $statusClass,
      ];
    }

    return $clients;
  }

  public function getBeneficiariesForClient(?int $clientId): array
  {
    if ($clientId === null) {
      return [];
    }

    $rows = $this->fetchAll(
      "SELECT id, relation, first_name, middle_name, last_name, birthdate, gender
       FROM client_beneficiaries
       WHERE client_id = :client_id
       ORDER BY created_at DESC",
      [
        ":client_id" => $clientId,
      ]
    );

    $beneficiaries = [];
    $index = 1;
    foreach ($rows as $row) {
      $row["index"] = $index;
      $beneficiaries[] = $row;
      $index += 1;
    }

    return $beneficiaries;
  }

  public function getClientById(?int $clientId): array
  {
    if ($clientId === null) {
      return [
        "branch_id" => null,
        "first_name" => "",
        "middle_name" => "",
        "last_name" => "",
        "borrower_id" => "",
        "phone" => "",
        "email" => "",
        "risk_category" => "",
        "verification_status" => "",
        "address" => "",
        "emergency_contact" => "",
        "last_review_date" => "",
        "assigned_officer" => "",
      ];
    }

    $row = $this->fetchOne(
      "SELECT
        branch_id,
        borrower_id,
        first_name,
        middle_name,
        last_name,
        phone_primary,
        email,
        risk_category,
        verification_status,
        present_address,
        emergency_contact,
        last_review_date,
        assigned_officer
       FROM clients
       WHERE id = :id
       LIMIT 1",
      [
        ":id" => $clientId,
      ]
    );

    if ($row === null) {
      return [
        "branch_id" => null,
        "first_name" => "",
        "middle_name" => "",
        "last_name" => "",
        "borrower_id" => "",
        "phone" => "",
        "email" => "",
        "risk_category" => "",
        "verification_status" => "",
        "address" => "",
        "emergency_contact" => "",
        "last_review_date" => "",
        "assigned_officer" => "",
      ];
    }

    return [
      "branch_id" => $row["branch_id"] ?? null,
      "first_name" => (string) ($row["first_name"] ?? ""),
      "middle_name" => (string) ($row["middle_name"] ?? ""),
      "last_name" => (string) ($row["last_name"] ?? ""),
      "borrower_id" => (string) ($row["borrower_id"] ?? ""),
      "phone" => (string) ($row["phone_primary"] ?? ""),
      "email" => (string) ($row["email"] ?? ""),
      "risk_category" => (string) ($row["risk_category"] ?? ""),
      "verification_status" => (string) ($row["verification_status"] ?? ""),
      "address" => (string) ($row["present_address"] ?? ""),
      "emergency_contact" => (string) ($row["emergency_contact"] ?? ""),
      "last_review_date" => (string) ($row["last_review_date"] ?? ""),
      "assigned_officer" => (string) ($row["assigned_officer"] ?? ""),
    ];
  }

  public function createClient(array $data): int
  {
    $this->execute(
      "INSERT INTO clients (
        branch_id,
        client_type,
        borrower_id,
        last_name,
        first_name,
        middle_name,
        birthdate,
        birthplace,
        nationality,
        gender,
        civil_status,
        email,
        facebook,
        source_of_fund,
        employment_occupation,
        employer_name,
        employment_address,
        employment_barangay,
        employment_position,
        employment_contact,
        employment_year_started,
        employment_gross_monthly_income,
        business_name,
        business_address,
        business_barangay,
        business_contact,
        business_year_started,
        business_gross_monthly_income,
        other_occupation,
        other_source_of_income,
        other_gross_monthly_income,
        phone_primary,
        phone_secondary,
        landline_primary,
        landline_secondary,
        present_address,
        permanent_address,
        emergency_contact,
        emergency_phone,
        id_number,
        secondary_id,
        secondary_id_expiry
      ) VALUES (
        :branch_id,
        :client_type,
        :borrower_id,
        :last_name,
        :first_name,
        :middle_name,
        :birthdate,
        :birthplace,
        :nationality,
        :gender,
        :civil_status,
        :email,
        :facebook,
        :source_of_fund,
        :employment_occupation,
        :employer_name,
        :employment_address,
        :employment_barangay,
        :employment_position,
        :employment_contact,
        :employment_year_started,
        :employment_gross_monthly_income,
        :business_name,
        :business_address,
        :business_barangay,
        :business_contact,
        :business_year_started,
        :business_gross_monthly_income,
        :other_occupation,
        :other_source_of_income,
        :other_gross_monthly_income,
        :phone_primary,
        :phone_secondary,
        :landline_primary,
        :landline_secondary,
        :present_address,
        :permanent_address,
        :emergency_contact,
        :emergency_phone,
        :id_number,
        :secondary_id,
        :secondary_id_expiry
      )",
      [
        ":branch_id" => $data["branch_id"],
        ":client_type" => $data["client_type"],
        ":borrower_id" => $data["borrower_id"],
        ":last_name" => $data["last_name"],
        ":first_name" => $data["first_name"],
        ":middle_name" => $data["middle_name"],
        ":birthdate" => $data["birthdate"],
        ":birthplace" => $data["birthplace"],
        ":nationality" => $data["nationality"],
        ":gender" => $data["gender"],
        ":civil_status" => $data["civil_status"],
        ":email" => $data["email"],
        ":facebook" => $data["facebook"],
        ":source_of_fund" => $data["source_of_fund"],
        ":employment_occupation" => $data["employment_occupation"],
        ":employer_name" => $data["employer_name"],
        ":employment_address" => $data["employment_address"],
        ":employment_barangay" => $data["employment_barangay"],
        ":employment_position" => $data["employment_position"],
        ":employment_contact" => $data["employment_contact"],
        ":employment_year_started" => $data["employment_year_started"],
        ":employment_gross_monthly_income" => $data["employment_gross_monthly_income"],
        ":business_name" => $data["business_name"],
        ":business_address" => $data["business_address"],
        ":business_barangay" => $data["business_barangay"],
        ":business_contact" => $data["business_contact"],
        ":business_year_started" => $data["business_year_started"],
        ":business_gross_monthly_income" => $data["business_gross_monthly_income"],
        ":other_occupation" => $data["other_occupation"],
        ":other_source_of_income" => $data["other_source_of_income"],
        ":other_gross_monthly_income" => $data["other_gross_monthly_income"],
        ":phone_primary" => $data["phone_primary"],
        ":phone_secondary" => $data["phone_secondary"],
        ":landline_primary" => $data["landline_primary"],
        ":landline_secondary" => $data["landline_secondary"],
        ":present_address" => $data["present_address"],
        ":permanent_address" => $data["permanent_address"],
        ":emergency_contact" => $data["emergency_contact"],
        ":emergency_phone" => $data["emergency_phone"],
        ":id_number" => $data["id_number"],
        ":secondary_id" => $data["secondary_id"],
        ":secondary_id_expiry" => $data["secondary_id_expiry"],
      ]
    );

    return (int) $this->db()->lastInsertId();
  }

  public function updateClient(int $clientId, array $data): bool
  {
    if (empty($data)) {
      return false;
    }

    $columns = [];
    $params = [":id" => $clientId];

    foreach ($data as $key => $value) {
      $columns[] = "{$key} = :{$key}";
      $params[":{$key}"] = $value;
    }

    $sql = "UPDATE clients SET " . implode(", ", $columns) . " WHERE id = :id";
    return $this->execute($sql, $params);
  }

  public function deleteClient(int $clientId): bool
  {
    return $this->execute(
      "DELETE FROM clients WHERE id = :id",
      [
        ":id" => $clientId,
      ]
    );
  }

  public function borrowerIdExists(string $borrowerId, ?int $excludeId = null): bool
  {
    $sql = "SELECT id FROM clients WHERE borrower_id = :borrower_id";
    $params = [":borrower_id" => $borrowerId];

    if ($excludeId !== null) {
      $sql .= " AND id <> :exclude_id";
      $params[":exclude_id"] = $excludeId;
    }

    $row = $this->fetchOne($sql . " LIMIT 1", $params);
    return $row !== null;
  }

  public function generateBorrowerId(): string
  {
    $row = $this->fetchOne(
      "SELECT MAX(CAST(SUBSTRING(borrower_id, 4) AS UNSIGNED)) AS max_id
       FROM clients
       WHERE borrower_id LIKE 'BR-%'"
    );

    $next = ((int) ($row["max_id"] ?? 0)) + 1;
    return sprintf("BR-%06d", $next);
  }

  public function createBeneficiary(int $clientId, array $data): int
  {
    $this->execute(
      "INSERT INTO client_beneficiaries (
        client_id,
        relation,
        first_name,
        middle_name,
        last_name,
        birthdate,
        gender
      ) VALUES (
        :client_id,
        :relation,
        :first_name,
        :middle_name,
        :last_name,
        :birthdate,
        :gender
      )",
      [
        ":client_id" => $clientId,
        ":relation" => $data["relation"],
        ":first_name" => $data["first_name"],
        ":middle_name" => $data["middle_name"],
        ":last_name" => $data["last_name"],
        ":birthdate" => $data["birthdate"],
        ":gender" => $data["gender"],
      ]
    );

    return (int) $this->db()->lastInsertId();
  }

  public function deleteBeneficiary(int $beneficiaryId, int $clientId): bool
  {
    return $this->execute(
      "DELETE FROM client_beneficiaries WHERE id = :id AND client_id = :client_id",
      [
        ":id" => $beneficiaryId,
        ":client_id" => $clientId,
      ]
    );
  }

  public function findByBorrowerId(string $borrowerId): ?array
  {
    return $this->fetchOne(
      "SELECT id, borrower_id, first_name, last_name
       FROM clients
       WHERE borrower_id = :borrower_id
       LIMIT 1",
      [
        ":borrower_id" => $borrowerId,
      ]
    );
  }
}
