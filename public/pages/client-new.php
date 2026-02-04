<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Client Management";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "New Client";
  $activePage = "client-new";

  $clientRepo = new ClientRepository();
  $settingsRepo = new SettingsRepository();
  $branchRepo = new BranchRepository();

  $branches = $branchRepo->getAllBranches();

  $errors = [];
  $formValues = [
    "client_type" => "Individual",
    "branch_id" => "",
    "borrower_id" => "",
    "last_name" => "",
    "first_name" => "",
    "middle_name" => "",
    "birthdate" => "",
    "birthplace" => "",
    "nationality" => "",
    "gender" => "",
    "civil_status" => "",
    "email" => "",
    "facebook" => "",
    "phone_primary" => "",
    "phone_secondary" => "",
    "landline_primary" => "",
    "landline_secondary" => "",
    "present_address" => "",
    "permanent_address" => "",
    "emergency_contact" => "",
    "emergency_phone" => "",
    "id_number" => "",
    "secondary_id" => "",
    "secondary_id_expiry" => "",
    "source_of_fund" => "none",
    "employment_occupation" => "",
    "employer_name" => "",
    "employment_address" => "",
    "employment_barangay" => "",
    "employment_position" => "",
    "employment_contact" => "",
    "employment_year_started" => "",
    "employment_gross_monthly_income" => "",
    "business_name" => "",
    "business_address" => "",
    "business_barangay" => "",
    "business_contact" => "",
    "business_year_started" => "",
    "business_gross_monthly_income" => "",
    "other_occupation" => "",
    "other_source_of_income" => "",
    "other_gross_monthly_income" => "",
  ];

  $normalizeText = function ($value): ?string {
    $value = trim((string) $value);
    return $value === "" ? null : $value;
  };

  $normalizeNumber = function ($value, string $label) use (&$errors): ?string {
    $value = trim((string) $value);
    if ($value === "") {
      return null;
    }
    $normalized = str_replace([",", " "], "", $value);
    if (!is_numeric($normalized)) {
      $errors[] = $label . " must be a number.";
      return null;
    }
    return $normalized;
  };

  $normalizeYear = function ($value, string $label) use (&$errors): ?int {
    $value = trim((string) $value);
    if ($value === "") {
      return null;
    }
    if (!ctype_digit($value) || strlen($value) !== 4) {
      $errors[] = $label . " must be a 4-digit year.";
      return null;
    }
    return (int) $value;
  };

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "create_client") {
      $formValues = array_merge($formValues, [
        "client_type" => trim((string) ($_POST["client_type"] ?? "Individual")),
        "branch_id" => trim((string) ($_POST["branch_id"] ?? "")),
        "borrower_id" => trim((string) ($_POST["borrower_id"] ?? "")),
        "last_name" => trim((string) ($_POST["last_name"] ?? "")),
        "first_name" => trim((string) ($_POST["first_name"] ?? "")),
        "middle_name" => trim((string) ($_POST["middle_name"] ?? "")),
        "birthdate" => trim((string) ($_POST["birthdate"] ?? "")),
        "birthplace" => trim((string) ($_POST["birthplace"] ?? "")),
        "nationality" => trim((string) ($_POST["nationality"] ?? "")),
        "gender" => trim((string) ($_POST["gender"] ?? "")),
        "civil_status" => trim((string) ($_POST["civil_status"] ?? "")),
        "email" => trim((string) ($_POST["email"] ?? "")),
        "facebook" => trim((string) ($_POST["facebook"] ?? "")),
        "phone_primary" => trim((string) ($_POST["phone_primary"] ?? "")),
        "phone_secondary" => trim((string) ($_POST["phone_secondary"] ?? "")),
        "landline_primary" => trim((string) ($_POST["landline_primary"] ?? "")),
        "landline_secondary" => trim((string) ($_POST["landline_secondary"] ?? "")),
        "present_address" => trim((string) ($_POST["present_address"] ?? "")),
        "permanent_address" => trim((string) ($_POST["permanent_address"] ?? "")),
        "emergency_contact" => trim((string) ($_POST["emergency_contact"] ?? "")),
        "emergency_phone" => trim((string) ($_POST["emergency_phone"] ?? "")),
        "id_number" => trim((string) ($_POST["id_number"] ?? "")),
        "secondary_id" => trim((string) ($_POST["secondary_id"] ?? "")),
        "secondary_id_expiry" => trim((string) ($_POST["secondary_id_expiry"] ?? "")),
        "source_of_fund" => trim((string) ($_POST["source_of_fund"] ?? "none")),
        "employment_occupation" => trim((string) ($_POST["employment_occupation"] ?? "")),
        "employer_name" => trim((string) ($_POST["employer_name"] ?? "")),
        "employment_address" => trim((string) ($_POST["employment_address"] ?? "")),
        "employment_barangay" => trim((string) ($_POST["employment_barangay"] ?? "")),
        "employment_position" => trim((string) ($_POST["employment_position"] ?? "")),
        "employment_contact" => trim((string) ($_POST["employment_contact"] ?? "")),
        "employment_year_started" => trim((string) ($_POST["employment_year_started"] ?? "")),
        "employment_gross_monthly_income" => trim((string) ($_POST["employment_gross_monthly_income"] ?? "")),
        "business_name" => trim((string) ($_POST["business_name"] ?? "")),
        "business_address" => trim((string) ($_POST["business_address"] ?? "")),
        "business_barangay" => trim((string) ($_POST["business_barangay"] ?? "")),
        "business_contact" => trim((string) ($_POST["business_contact"] ?? "")),
        "business_year_started" => trim((string) ($_POST["business_year_started"] ?? "")),
        "business_gross_monthly_income" => trim((string) ($_POST["business_gross_monthly_income"] ?? "")),
        "other_occupation" => trim((string) ($_POST["other_occupation"] ?? "")),
        "other_source_of_income" => trim((string) ($_POST["other_source_of_income"] ?? "")),
        "other_gross_monthly_income" => trim((string) ($_POST["other_gross_monthly_income"] ?? "")),
      ]);

      if ($formValues["first_name"] === "") {
        $errors[] = "First name is required.";
      }

      if ($formValues["last_name"] === "") {
        $errors[] = "Last name is required.";
      }

      $branchId = null;
      if ($formValues["branch_id"] === "" || !ctype_digit($formValues["branch_id"])) {
        $errors[] = "Branch is required.";
      } else {
        $branchId = (int) $formValues["branch_id"];
      }

      if (empty($branches)) {
        $errors[] = "No branches available. Please add a branch first.";
      }

      $borrowerId = $formValues["borrower_id"];
      if ($borrowerId !== "" && $clientRepo->borrowerIdExists($borrowerId)) {
        $errors[] = "Borrower ID already exists.";
      }
      if ($borrowerId === "") {
        $borrowerId = $clientRepo->generateBorrowerId();
      }

      $allowedClientTypes = ["Individual", "Business"];
      if (!in_array($formValues["client_type"], $allowedClientTypes, true)) {
        $formValues["client_type"] = "Individual";
      }

      $allowedGender = ["Female", "Male", "Other"];
      if ($formValues["gender"] !== "" && !in_array($formValues["gender"], $allowedGender, true)) {
        $errors[] = "Invalid gender selection.";
      }

      $allowedCivil = ["Single", "Married", "Separated", "Widowed"];
      if ($formValues["civil_status"] !== "" && !in_array($formValues["civil_status"], $allowedCivil, true)) {
        $errors[] = "Invalid civil status selection.";
      }

      $sourceMap = [
        "none" => "None",
        "employment" => "Employment",
        "business" => "Business",
        "others" => "Others",
      ];
      $sourceKey = $formValues["source_of_fund"];
      $sourceOfFund = $sourceMap[$sourceKey] ?? "None";

      $employmentIncome = null;
      $businessIncome = null;
      $otherIncome = null;
      $employmentYear = null;
      $businessYear = null;

      if ($sourceKey === "employment") {
        $employmentIncome = $normalizeNumber($formValues["employment_gross_monthly_income"], "Employment gross monthly income");
        $employmentYear = $normalizeYear($formValues["employment_year_started"], "Year started work");
      }

      if ($sourceKey === "business") {
        $businessIncome = $normalizeNumber($formValues["business_gross_monthly_income"], "Business gross monthly income");
        $businessYear = $normalizeYear($formValues["business_year_started"], "Year business started");
      }

      if ($sourceKey === "others") {
        $otherIncome = $normalizeNumber($formValues["other_gross_monthly_income"], "Other gross monthly income");
      }

      if (empty($errors)) {
        $payload = [
          "branch_id" => $branchId,
          "client_type" => $formValues["client_type"],
          "borrower_id" => $borrowerId,
          "last_name" => $formValues["last_name"],
          "first_name" => $formValues["first_name"],
          "middle_name" => $normalizeText($formValues["middle_name"]),
          "birthdate" => $normalizeText($formValues["birthdate"]),
          "birthplace" => $normalizeText($formValues["birthplace"]),
          "nationality" => $normalizeText($formValues["nationality"]),
          "gender" => $normalizeText($formValues["gender"]),
          "civil_status" => $normalizeText($formValues["civil_status"]),
          "email" => $normalizeText($formValues["email"]),
          "facebook" => $normalizeText($formValues["facebook"]),
          "source_of_fund" => $sourceOfFund,
          "employment_occupation" => $sourceKey === "employment" ? $normalizeText($formValues["employment_occupation"]) : null,
          "employer_name" => $sourceKey === "employment" ? $normalizeText($formValues["employer_name"]) : null,
          "employment_address" => $sourceKey === "employment" ? $normalizeText($formValues["employment_address"]) : null,
          "employment_barangay" => $sourceKey === "employment" ? $normalizeText($formValues["employment_barangay"]) : null,
          "employment_position" => $sourceKey === "employment" ? $normalizeText($formValues["employment_position"]) : null,
          "employment_contact" => $sourceKey === "employment" ? $normalizeText($formValues["employment_contact"]) : null,
          "employment_year_started" => $sourceKey === "employment" ? $employmentYear : null,
          "employment_gross_monthly_income" => $sourceKey === "employment" ? $employmentIncome : null,
          "business_name" => $sourceKey === "business" ? $normalizeText($formValues["business_name"]) : null,
          "business_address" => $sourceKey === "business" ? $normalizeText($formValues["business_address"]) : null,
          "business_barangay" => $sourceKey === "business" ? $normalizeText($formValues["business_barangay"]) : null,
          "business_contact" => $sourceKey === "business" ? $normalizeText($formValues["business_contact"]) : null,
          "business_year_started" => $sourceKey === "business" ? $businessYear : null,
          "business_gross_monthly_income" => $sourceKey === "business" ? $businessIncome : null,
          "other_occupation" => $sourceKey === "others" ? $normalizeText($formValues["other_occupation"]) : null,
          "other_source_of_income" => $sourceKey === "others" ? $normalizeText($formValues["other_source_of_income"]) : null,
          "other_gross_monthly_income" => $sourceKey === "others" ? $otherIncome : null,
          "phone_primary" => $normalizeText($formValues["phone_primary"]),
          "phone_secondary" => $normalizeText($formValues["phone_secondary"]),
          "landline_primary" => $normalizeText($formValues["landline_primary"]),
          "landline_secondary" => $normalizeText($formValues["landline_secondary"]),
          "present_address" => $normalizeText($formValues["present_address"]),
          "permanent_address" => $normalizeText($formValues["permanent_address"]),
          "emergency_contact" => $normalizeText($formValues["emergency_contact"]),
          "emergency_phone" => $normalizeText($formValues["emergency_phone"]),
          "id_number" => $normalizeText($formValues["id_number"]),
          "secondary_id" => $normalizeText($formValues["secondary_id"]),
          "secondary_id_expiry" => $normalizeText($formValues["secondary_id_expiry"]),
        ];

        $beneficiariesInput = $_POST["beneficiaries"] ?? [];
        $beneficiariesToCreate = [];

        if (is_array($beneficiariesInput)) {
          foreach ($beneficiariesInput as $index => $beneficiary) {
            if (!is_array($beneficiary)) {
              continue;
            }
            $relation = trim((string) ($beneficiary["relation"] ?? ""));
            $firstName = trim((string) ($beneficiary["first_name"] ?? ""));
            $lastName = trim((string) ($beneficiary["last_name"] ?? ""));

            if ($relation === "" || $firstName === "" || $lastName === "") {
              $errors[] = "Beneficiary " . ($index + 1) . " requires relation, first name, and last name.";
              continue;
            }

            $beneficiariesToCreate[] = [
              "relation" => $relation,
              "first_name" => $firstName,
              "middle_name" => $normalizeText($beneficiary["middle_name"] ?? ""),
              "last_name" => $lastName,
              "birthdate" => $normalizeText($beneficiary["birthdate"] ?? ""),
              "gender" => $normalizeText($beneficiary["gender"] ?? ""),
            ];
          }
        }

        if (empty($errors)) {
          $newClientId = $clientRepo->withTransaction(function () use ($clientRepo, $payload, $beneficiariesToCreate) {
            $clientId = $clientRepo->createClient($payload);
            foreach ($beneficiariesToCreate as $beneficiary) {
              $clientRepo->createBeneficiary($clientId, $beneficiary);
            }
            return $clientId;
          });

          header("Location: client-edit.php?id=" . $newClientId);
          exit;
        }
      }
    }
  }

  $clientStats = $clientRepo->getDashboardStats();
  $recentClients = $clientRepo->getRecentClients();
  $beneficiaries = $clientRepo->getBeneficiariesForClient(null);
  $loanProducts = $settingsRepo->getLoanProducts();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Keep every borrower profile clean and auditable.</h2>
    <p>
      Review identity, contact details, and risk tags in one flow.
      All changes are tracked by branch.
    </p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $clientStats["active"]; ?></strong>
        <span>Active borrowers</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["pending_verification"]; ?></strong>
        <span>Pending verification</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["new_applications"]; ?></strong>
        <span>New applications</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["high_risk"]; ?></strong>
        <span>High-risk flags</span>
      </div>
    </div>
  </section>

  <form class="grid grid-2" style="margin-top: 24px;" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
    <input type="hidden" name="action" value="create_client" />
    <?php if (!empty($errors)) : ?>
      <div class="form-error" style="grid-column: 1 / -1;">
        <strong>Please review the errors below:</strong>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <section class="card">
      <div class="section-title">
        <h3>Client Intake</h3>
        <div>
          <button class="btn ghost" type="button">Save Draft</button>
          <button class="btn" type="submit">Create Client</button>
        </div>
      </div>

      <div class="tabs">
        <div class="tab active">Basic Info</div>
        <div class="tab">Other Info</div>
        <div class="tab">Beneficiaries</div>
      </div>

      <div class="form-grid">
        <div>
          <label>Client Type</label>
          <select name="client_type">
            <option value="Individual" <?php echo $formValues["client_type"] === "Individual" ? "selected" : ""; ?>>Individual</option>
            <option value="Business" <?php echo $formValues["client_type"] === "Business" ? "selected" : ""; ?>>Business</option>
          </select>
        </div>
        <div>
          <label>Branch</label>
          <select name="branch_id">
            <option value="">Select branch</option>
            <?php foreach ($branches as $branch) : ?>
              <?php $branchId = (string) ($branch["id"] ?? ""); ?>
              <option value="<?php echo htmlspecialchars($branchId); ?>" <?php echo $branchId === (string) $formValues["branch_id"] ? "selected" : ""; ?>>
                <?php echo htmlspecialchars((string) ($branch["code"] ?? "")); ?> - <?php echo htmlspecialchars((string) ($branch["name"] ?? "")); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Last Name</label>
          <input type="text" name="last_name" placeholder="Dela Cruz" value="<?php echo htmlspecialchars($formValues["last_name"]); ?>" />
        </div>
        <div>
          <label>First Name</label>
          <input type="text" name="first_name" placeholder="Maria" value="<?php echo htmlspecialchars($formValues["first_name"]); ?>" />
        </div>
        <div>
          <label>Middle Name</label>
          <input type="text" name="middle_name" placeholder="G." value="<?php echo htmlspecialchars($formValues["middle_name"]); ?>" />
        </div>
        <div>
          <label>Birthdate</label>
          <input type="date" name="birthdate" value="<?php echo htmlspecialchars($formValues["birthdate"]); ?>" />
        </div>
        <div>
          <label>Birthplace</label>
          <input type="text" name="birthplace" placeholder="Cebu City" value="<?php echo htmlspecialchars($formValues["birthplace"]); ?>" />
        </div>
        <div>
          <label>Nationality</label>
          <input type="text" name="nationality" placeholder="Filipino" value="<?php echo htmlspecialchars($formValues["nationality"]); ?>" />
        </div>
        <div>
          <label>Gender</label>
          <select name="gender">
            <option value="">Select</option>
            <option value="Female" <?php echo $formValues["gender"] === "Female" ? "selected" : ""; ?>>Female</option>
            <option value="Male" <?php echo $formValues["gender"] === "Male" ? "selected" : ""; ?>>Male</option>
            <option value="Other" <?php echo $formValues["gender"] === "Other" ? "selected" : ""; ?>>Other</option>
          </select>
        </div>
        <div>
          <label>Civil Status</label>
          <select name="civil_status">
            <option value="">Select</option>
            <option value="Single" <?php echo $formValues["civil_status"] === "Single" ? "selected" : ""; ?>>Single</option>
            <option value="Married" <?php echo $formValues["civil_status"] === "Married" ? "selected" : ""; ?>>Married</option>
            <option value="Separated" <?php echo $formValues["civil_status"] === "Separated" ? "selected" : ""; ?>>Separated</option>
            <option value="Widowed" <?php echo $formValues["civil_status"] === "Widowed" ? "selected" : ""; ?>>Widowed</option>
          </select>
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" name="email" placeholder="name@email.com" value="<?php echo htmlspecialchars($formValues["email"]); ?>" />
        </div>
        <div>
          <label>Facebook Account</label>
          <input type="text" name="facebook" placeholder="facebook.com/profile" value="<?php echo htmlspecialchars($formValues["facebook"]); ?>" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Cellphone No. 1</label>
          <input type="text" name="phone_primary" placeholder="+63 9xx xxx xxxx" value="<?php echo htmlspecialchars($formValues["phone_primary"]); ?>" />
        </div>
        <div>
          <label>Cellphone No. 2</label>
          <input type="text" name="phone_secondary" placeholder="+63 9xx xxx xxxx" value="<?php echo htmlspecialchars($formValues["phone_secondary"]); ?>" />
        </div>
        <div>
          <label>Landline No. 1</label>
          <input type="text" name="landline_primary" placeholder="(02) 8xxx xxxx" value="<?php echo htmlspecialchars($formValues["landline_primary"]); ?>" />
        </div>
        <div>
          <label>Landline No. 2</label>
          <input type="text" name="landline_secondary" placeholder="(02) 8xxx xxxx" value="<?php echo htmlspecialchars($formValues["landline_secondary"]); ?>" />
        </div>
        <div>
          <label>Present Address</label>
          <input type="text" name="present_address" placeholder="Street, Barangay" value="<?php echo htmlspecialchars($formValues["present_address"]); ?>" />
        </div>
        <div>
          <label>Permanent Address</label>
          <input type="text" name="permanent_address" placeholder="Street, Barangay" value="<?php echo htmlspecialchars($formValues["permanent_address"]); ?>" />
        </div>
        <div>
          <label>Emergency Contact</label>
          <input type="text" name="emergency_contact" placeholder="Contact person" value="<?php echo htmlspecialchars($formValues["emergency_contact"]); ?>" />
        </div>
        <div>
          <label>Contact No.</label>
          <input type="text" name="emergency_phone" placeholder="09xx xxx xxxx" value="<?php echo htmlspecialchars($formValues["emergency_phone"]); ?>" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Borrower ID</label>
          <input type="text" name="borrower_id" placeholder="BR-000245" value="<?php echo htmlspecialchars($formValues["borrower_id"]); ?>" />
        </div>
        <div>
          <label>ID Number</label>
          <input type="text" name="id_number" placeholder="0000-0000-0000" value="<?php echo htmlspecialchars($formValues["id_number"]); ?>" />
        </div>
        <div>
          <label>Secondary ID</label>
          <input type="text" name="secondary_id" placeholder="Driver License" value="<?php echo htmlspecialchars($formValues["secondary_id"]); ?>" />
        </div>
        <div>
          <label>Secondary ID Expiry</label>
          <input type="date" name="secondary_id_expiry" value="<?php echo htmlspecialchars($formValues["secondary_id_expiry"]); ?>" />
        </div>
      </div>

      <div class="divider"></div>

      <div>
        <label>Risk Category</label>
        <div class="tag-row" style="margin-top: 8px;">
          <span class="tag">Undefined</span>
          <span class="tag">VIP</span>
          <span class="tag">DOSRI</span>
          <span class="tag">RPT</span>
          <span class="tag">PEP</span>
        </div>
      </div>
    </section>

    <div class="grid" style="gap: 16px;">
      <section class="card soft">
        <div class="section-title">
          <h3>Other Info</h3>
          <button class="btn ghost" type="button">Edit</button>
        </div>
        <div class="form-grid">
          <div>
            <label>Height (Meters)</label>
            <input type="text" placeholder="1.62" />
          </div>
          <div>
            <label>Height (ft/in)</label>
            <input type="text" placeholder="5'4" />
          </div>
          <div>
            <label>Weight (Kg)</label>
            <input type="text" placeholder="52" />
          </div>
          <div>
            <label>Weight (Lbs)</label>
            <input type="text" placeholder="115" />
          </div>
          <div style="grid-column: 1 / -1;">
            <label>Source of Fund / Employment Details</label>
            <div class="radio-group" role="radiogroup" aria-label="Source of Fund">
              <label class="radio-option">
                <input type="radio" name="source_of_fund" value="none" <?php echo $formValues["source_of_fund"] === "none" ? "checked" : ""; ?> />
                None
              </label>
              <label class="radio-option">
                <input type="radio" name="source_of_fund" value="employment" <?php echo $formValues["source_of_fund"] === "employment" ? "checked" : ""; ?> />
                Employment
              </label>
              <label class="radio-option">
                <input type="radio" name="source_of_fund" value="business" <?php echo $formValues["source_of_fund"] === "business" ? "checked" : ""; ?> />
                Business
              </label>
              <label class="radio-option">
                <input type="radio" name="source_of_fund" value="others" <?php echo $formValues["source_of_fund"] === "others" ? "checked" : ""; ?> />
                Others
              </label>
            </div>
          </div>
          <div class="source-panel" data-source-panel data-source="employment" style="grid-column: 1 / -1;">
            <div class="source-panel-title">Employment Details</div>
            <div class="form-grid">
              <div>
                <label>Occupation</label>
                <select name="employment_occupation">
                  <option value="">Select</option>
                  <option value="Self-Employed" <?php echo $formValues["employment_occupation"] === "Self-Employed" ? "selected" : ""; ?>>Self-Employed</option>
                  <option value="Employed" <?php echo $formValues["employment_occupation"] === "Employed" ? "selected" : ""; ?>>Employed</option>
                  <option value="Professional Practitioner" <?php echo $formValues["employment_occupation"] === "Professional Practitioner" ? "selected" : ""; ?>>Professional Practitioner</option>
                  <option value="Private Employee" <?php echo $formValues["employment_occupation"] === "Private Employee" ? "selected" : ""; ?>>Private Employee</option>
                  <option value="Politician" <?php echo $formValues["employment_occupation"] === "Politician" ? "selected" : ""; ?>>Politician</option>
                </select>
              </div>
              <div>
                <label>Employer Name</label>
                <input type="text" name="employer_name" placeholder="Company Name" value="<?php echo htmlspecialchars($formValues["employer_name"]); ?>" />
              </div>
              <div>
                <label>Employment Address</label>
                <input type="text" name="employment_address" placeholder="Street, City" value="<?php echo htmlspecialchars($formValues["employment_address"]); ?>" />
              </div>
              <div>
                <label>Address Barangay</label>
                <input type="text" name="employment_barangay" placeholder="Barangay" value="<?php echo htmlspecialchars($formValues["employment_barangay"]); ?>" />
              </div>
              <div>
                <label>Employment Position</label>
                <input type="text" name="employment_position" placeholder="Branch Manager" value="<?php echo htmlspecialchars($formValues["employment_position"]); ?>" />
              </div>
              <div>
                <label>Contact Number</label>
                <input type="text" name="employment_contact" placeholder="09xx xxx xxxx" value="<?php echo htmlspecialchars($formValues["employment_contact"]); ?>" />
              </div>
              <div>
                <label>Year Started Work</label>
                <input type="text" name="employment_year_started" placeholder="2019" value="<?php echo htmlspecialchars($formValues["employment_year_started"]); ?>" />
              </div>
              <div>
                <label>Gross Monthly Income</label>
                <input type="text" name="employment_gross_monthly_income" placeholder="0.00" value="<?php echo htmlspecialchars($formValues["employment_gross_monthly_income"]); ?>" />
              </div>
            </div>
          </div>
          <div class="source-panel" data-source-panel data-source="business" style="grid-column: 1 / -1;">
            <div class="source-panel-title">Business Details</div>
            <div class="form-grid">
              <div>
                <label>Business Name</label>
                <input type="text" name="business_name" placeholder="Business Name" value="<?php echo htmlspecialchars($formValues["business_name"]); ?>" />
              </div>
              <div>
                <label>Business Address</label>
                <input type="text" name="business_address" placeholder="Street, City" value="<?php echo htmlspecialchars($formValues["business_address"]); ?>" />
              </div>
              <div>
                <label>Address Barangay</label>
                <input type="text" name="business_barangay" placeholder="Barangay" value="<?php echo htmlspecialchars($formValues["business_barangay"]); ?>" />
              </div>
              <div>
                <label>Contact Number</label>
                <input type="text" name="business_contact" placeholder="09xx xxx xxxx" value="<?php echo htmlspecialchars($formValues["business_contact"]); ?>" />
              </div>
              <div>
                <label>Year Business Started</label>
                <input type="text" name="business_year_started" placeholder="2019" value="<?php echo htmlspecialchars($formValues["business_year_started"]); ?>" />
              </div>
              <div>
                <label>Gross Monthly Income</label>
                <input type="text" name="business_gross_monthly_income" placeholder="0.00" value="<?php echo htmlspecialchars($formValues["business_gross_monthly_income"]); ?>" />
              </div>
            </div>
          </div>
          <div class="source-panel" data-source-panel data-source="others" style="grid-column: 1 / -1;">
            <div class="source-panel-title">Other Income Details</div>
            <div class="form-grid">
              <div>
                <label>Occupation</label>
                <input type="text" name="other_occupation" placeholder="Occupation" value="<?php echo htmlspecialchars($formValues["other_occupation"]); ?>" />
              </div>
              <div>
                <label>Source of Income</label>
                <input type="text" name="other_source_of_income" placeholder="Source of Income" value="<?php echo htmlspecialchars($formValues["other_source_of_income"]); ?>" />
              </div>
              <div>
                <label>Gross Monthly Income</label>
                <input type="text" name="other_gross_monthly_income" placeholder="0.00" value="<?php echo htmlspecialchars($formValues["other_gross_monthly_income"]); ?>" />
              </div>
            </div>
          </div>
          <div>
            <label>Mother's Maiden Last Name</label>
            <input type="text" placeholder="Santos" />
          </div>
          <div>
            <label>Mother's Maiden First Name</label>
            <input type="text" placeholder="Lourdes" />
          </div>
        </div>
      </section>

      <section class="card soft beneficiaries">
        <div class="section-title">
          <h3>Beneficiaries</h3>
          <button class="btn ghost" type="button">Add Existing Client</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Relation</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Birthdate</th>
              <th>Gender</th>
            </tr>
          </thead>
          <tbody>
            <tr class="beneficiary-entry" data-beneficiary-entry>
              <td>New</td>
              <td>
                <input type="text" placeholder="Relation" data-beneficiary-field="relation" />
              </td>
              <td>
                <input type="text" placeholder="First Name" data-beneficiary-field="first_name" />
              </td>
              <td>
                <input type="text" placeholder="Middle Name" data-beneficiary-field="middle_name" />
              </td>
              <td>
                <input type="text" placeholder="Last Name" data-beneficiary-field="last_name" />
              </td>
              <td>
                <input type="date" data-beneficiary-field="birthdate" />
              </td>
              <td>
                <div class="beneficiary-cell">
                  <select data-beneficiary-field="gender">
                    <option value="">Select</option>
                    <option>Female</option>
                    <option>Male</option>
                    <option>Other</option>
                  </select>
                  <button class="btn small" type="button" data-action="add-beneficiary">Add</button>
                </div>
              </td>
            </tr>
            <?php if (empty($beneficiaries)) : ?>
              <tr data-empty-row>
                <td colspan="7" class="empty-row">No beneficiaries added yet.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($beneficiaries as $beneficiary) : ?>
                <tr>
                  <td><?php echo htmlspecialchars((string) $beneficiary["index"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["relation"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["first_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["middle_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["last_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["birthdate"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["gender"]); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </div>
  </form>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Loan Products</h3>
        <button class="btn ghost">Manage Products</button>
      </div>
      <?php if (empty($loanProducts)) : ?>
        <div class="empty-row">No loan products configured yet.</div>
      <?php else : ?>
        <div class="product-grid">
          <?php foreach ($loanProducts as $product) : ?>
            <div class="product">
              <div class="badge"></div>
              <strong><?php echo htmlspecialchars((string) $product["name"]); ?></strong>
              <span>Interest Rate: <?php echo htmlspecialchars((string) $product["interest_rate"]); ?></span>
              <span>Service Charge: <?php echo htmlspecialchars((string) $product["service_charge"]); ?></span>
              <span class="status"><?php echo htmlspecialchars((string) $product["status"]); ?></span>
              <button class="cta">Select</button>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Clients</strong>
        <a href="#">View All</a>
      </header>
      <ul>
        <?php if (empty($recentClients)) : ?>
          <li class="empty-row">No recent clients yet.</li>
        <?php else : ?>
          <?php foreach ($recentClients as $client) : ?>
            <li>
              <span><?php echo htmlspecialchars((string) $client["name"]); ?></span>
              <a href="<?php echo htmlspecialchars((string) $client["edit_url"]); ?>">Edit</a>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
