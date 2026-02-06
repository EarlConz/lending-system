<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Application";
  $activePage = "loan-application";

  $loanRepo = new LoanRepository();
  $clientRepo = new ClientRepository();
  $settingsRepo = new SettingsRepository();
  $cacobemRepo = new CacobemRepository();
  $errors = [];
  $cacobemErrors = [];
  $cacobemSuccess = false;

  $loanProducts = $settingsRepo->getLoanProducts("Active");
  $clientPicklist = $clientRepo->getClientsForPicklist();
  $productIds = array_map(static function ($product) {
    return (int) $product["id"];
  }, $loanProducts);

  $formValues = [
    "borrower_id" => "",
    "product_id" => "",
    "savings_account" => "",
    "requested_amount" => "",
    "terms_months" => "",
    "term_unit" => "",
    "term_fixed" => 0,
    "interest_rate" => "",
    "interest_type" => "",
    "equal_principal" => 0,
    "release_date" => "",
    "maturity_date" => "",
    "deduction_interest" => "",
    "deduction_service_charge" => "",
    "deduction_climbs" => "",
    "deduction_notarial_fee" => "",
    "total_deductions" => "",
    "net_proceeds" => "",
    "amortization_days" => "",
    "principal_interval" => "",
    "interval_adjustment" => "",
    "fixed_amortization" => "",
    "irregular_amortization" => "",
    "insurance_amount" => "",
    "insurance_basis" => "Fixed Amount",
    "interest_amortized" => "Not Used",
    "service_charge_amortized" => "Not Used",
    "client_photo_path" => "",
  ];

  $clientInfo = [
    "name" => "",
    "phone" => "",
  ];

  $cacobemFieldKeys = [
    "application_date",
    "borrower_name",
    "borrower_age",
    "ctc_no",
    "ctc_date_issued",
    "birthdate",
    "birth_place",
    "place_issued",
    "spouse_name",
    "spouse_age",
    "children_count",
    "address",
    "amount_applied",
    "specific_purpose",
    "borrower_signature",
    "spouse_signature",
    "comaker1_signature",
    "comaker2_signature",
    "authorization_date",
    "authorization_schedule",
    "authorization_amount_words",
    "authorization_amount_php",
    "authorization_borrower_signature",
    "discount_loan_type",
    "discount_term_days",
    "discount_date_granted",
    "discount_maturity_date",
    "discount_amount_loan",
    "discount_lb_int",
    "discount_notarial_fee",
    "discount_mri_insurance",
    "discount_total_deductions",
    "discount_net_proceeds",
    "discount_prepared_by",
    "discount_checked_by",
    "discount_net_proceeds_words",
    "discount_net_proceeds_php",
    "discount_bank_account",
    "discount_conformed_by",
    "action_loan_ceiling",
    "action_share_capital",
    "action_loan_balance",
    "action_interest_due",
    "action_remark",
    "action_certified_by",
    "action_certified_date",
    "action_security",
    "action_share_capital_security",
    "action_rem_tct_no",
    "action_chattel_mortgage",
    "action_approved_amount",
    "pn_no",
    "pn_date_granted",
    "pn_maturity_date",
    "pn_amount_granted",
    "pn_term_value",
    "pn_term_unit",
    "pn_amount_words",
    "pn_amount_php",
    "pn_secured_by",
    "pn_secured_date",
    "pn_doc_no",
    "pn_page_no",
    "pn_book_no",
    "pn_series_year",
    "pn_borrower_signature",
    "pn_spouse_signature",
    "pn_comaker1_signature",
    "pn_comaker2_signature",
    "witness_1",
    "witness_2",
  ];

  $defaultCacobemValues = array_fill_keys($cacobemFieldKeys, "");
  $cacobemFormValues = $defaultCacobemValues;

  $normalizeText = function ($value): ?string {
    $value = trim((string) $value);
    return $value === "" ? null : $value;
  };

  $normalizeNumber = function ($value, string $label, bool $required = false) use (&$errors): ?string {
    $value = trim((string) $value);
    if ($value === "") {
      if ($required) {
        $errors[] = $label . " is required.";
      }
      return null;
    }
    $normalized = str_replace([",", " "], "", $value);
    if (!is_numeric($normalized)) {
      $errors[] = $label . " must be a number.";
      return null;
    }
    return $normalized;
  };

  $normalizeInt = function ($value, string $label, bool $required = false) use (&$errors): ?int {
    $value = trim((string) $value);
    if ($value === "") {
      if ($required) {
        $errors[] = $label . " is required.";
      }
      return null;
    }
    if (!ctype_digit($value)) {
      $errors[] = $label . " must be a whole number.";
      return null;
    }
    return (int) $value;
  };

  $normalizeDate = function ($value, string $label, bool $required = false) use (&$errors): ?string {
    $value = trim((string) $value);
    if ($value === "") {
      if ($required) {
        $errors[] = $label . " is required.";
      }
      return null;
    }

    $date = DateTime::createFromFormat("Y-m-d", $value);
    if ($date === false || $date->format("Y-m-d") !== $value) {
      $errors[] = $label . " must be a valid date.";
      return null;
    }

    return $value;
  };

  $allowedTermUnits = ["Days", "Weeks", "Months", "Semi-Months"];
  $allowedInterestTypes = ["Diminishing", "Flat"];
  $allowedUsedOptions = ["Not Used", "Used"];
  $allowedInsuranceBasis = ["Fixed Amount", "Percent"];
  $allowedAmortizationDays = [
    "Every 15th and End of the Month",
    "Every Semi-Month",
    "Every Month",
    "Every Week",
    "Custom",
  ];
  $allowedPrincipalIntervals = ["Every Semi-Month", "Every Month", "Every Week", "Custom"];
  $allowedIntervalAdjustments = ["None", "Next Banking Day", "Previous Banking Day"];

  $photoUpload = null;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "create_application") {
      $formValues = array_merge($formValues, [
        "borrower_id" => trim((string) ($_POST["borrower_id"] ?? "")),
        "product_id" => trim((string) ($_POST["product_id"] ?? "")),
        "savings_account" => trim((string) ($_POST["savings_account"] ?? "")),
        "requested_amount" => trim((string) ($_POST["requested_amount"] ?? "")),
        "terms_months" => trim((string) ($_POST["terms_months"] ?? "")),
        "term_unit" => trim((string) ($_POST["term_unit"] ?? "")),
        "term_fixed" => isset($_POST["term_fixed"]) ? 1 : 0,
        "interest_rate" => trim((string) ($_POST["interest_rate"] ?? "")),
        "interest_type" => trim((string) ($_POST["interest_type"] ?? "")),
        "equal_principal" => isset($_POST["equal_principal"]) ? 1 : 0,
        "release_date" => trim((string) ($_POST["release_date"] ?? "")),
        "maturity_date" => trim((string) ($_POST["maturity_date"] ?? "")),
        "deduction_interest" => trim((string) ($_POST["deduction_interest"] ?? "")),
        "deduction_service_charge" => trim((string) ($_POST["deduction_service_charge"] ?? "")),
        "deduction_climbs" => trim((string) ($_POST["deduction_climbs"] ?? "")),
        "deduction_notarial_fee" => trim((string) ($_POST["deduction_notarial_fee"] ?? "")),
        "total_deductions" => trim((string) ($_POST["total_deductions"] ?? "")),
        "net_proceeds" => trim((string) ($_POST["net_proceeds"] ?? "")),
        "amortization_days" => trim((string) ($_POST["amortization_days"] ?? "")),
        "principal_interval" => trim((string) ($_POST["principal_interval"] ?? "")),
        "interval_adjustment" => trim((string) ($_POST["interval_adjustment"] ?? "")),
        "fixed_amortization" => trim((string) ($_POST["fixed_amortization"] ?? "")),
        "irregular_amortization" => trim((string) ($_POST["irregular_amortization"] ?? "")),
        "insurance_amount" => trim((string) ($_POST["insurance_amount"] ?? "")),
        "insurance_basis" => trim((string) ($_POST["insurance_basis"] ?? "Fixed Amount")),
        "interest_amortized" => trim((string) ($_POST["interest_amortized"] ?? "Not Used")),
        "service_charge_amortized" => trim((string) ($_POST["service_charge_amortized"] ?? "Not Used")),
      ]);

      if ($formValues["borrower_id"] === "") {
        $errors[] = "Borrower ID is required.";
      }

      $client = null;
      if ($formValues["borrower_id"] !== "") {
        $client = $clientRepo->findByBorrowerId($formValues["borrower_id"]);
        if ($client === null) {
          $errors[] = "Borrower ID not found.";
        }
      }

      if ($client !== null) {
        $clientDetails = $clientRepo->getClientById((int) $client["id"]);
        $nameParts = array_filter([
          $clientDetails["last_name"] ?? "",
          $clientDetails["first_name"] ?? "",
        ]);
        $clientInfo["name"] = trim(implode(", ", $nameParts));
        $clientInfo["phone"] = (string) ($clientDetails["phone"] ?? "");
      }

      $productId = $normalizeInt($formValues["product_id"], "Loan product", true);
      if ($productId !== null && !in_array($productId, $productIds, true)) {
        $errors[] = "Loan product not found.";
      }

      $requestedAmount = $normalizeNumber($formValues["requested_amount"], "Loan amount", true);
      $termsMonths = $normalizeInt($formValues["terms_months"], "Term", true);

      $termUnit = in_array($formValues["term_unit"], $allowedTermUnits, true)
        ? $formValues["term_unit"]
        : null;
      if ($termUnit === null) {
        $errors[] = "Term unit is required.";
      }

      $interestRate = $normalizeNumber($formValues["interest_rate"], "Interest rate");
      $interestType = in_array($formValues["interest_type"], $allowedInterestTypes, true)
        ? $formValues["interest_type"]
        : null;

      $releaseDate = $normalizeDate($formValues["release_date"], "Release date");
      $maturityDate = $normalizeDate($formValues["maturity_date"], "Maturity date");

      $deductionInterest = $normalizeNumber($formValues["deduction_interest"], "Deduction interest");
      $deductionServiceCharge = $normalizeNumber($formValues["deduction_service_charge"], "Service charge deduction");
      $deductionClimbs = $normalizeNumber($formValues["deduction_climbs"], "Climbs");
      $deductionNotarialFee = $normalizeNumber($formValues["deduction_notarial_fee"], "Notarial fee");
      $totalDeductions = $normalizeNumber($formValues["total_deductions"], "Total deductions");
      $netProceeds = $normalizeNumber($formValues["net_proceeds"], "Net proceeds");

      $amortizationDays = in_array($formValues["amortization_days"], $allowedAmortizationDays, true)
        ? $formValues["amortization_days"]
        : null;
      $principalInterval = in_array($formValues["principal_interval"], $allowedPrincipalIntervals, true)
        ? $formValues["principal_interval"]
        : null;
      $intervalAdjustment = in_array($formValues["interval_adjustment"], $allowedIntervalAdjustments, true)
        ? $formValues["interval_adjustment"]
        : null;

      $fixedAmortization = $normalizeNumber($formValues["fixed_amortization"], "Fixed amortization");
      $irregularAmortization = $normalizeNumber($formValues["irregular_amortization"], "Irregular amortization");
      $insuranceAmount = $normalizeNumber($formValues["insurance_amount"], "Insurance amount");
      $insuranceBasis = in_array($formValues["insurance_basis"], $allowedInsuranceBasis, true)
        ? $formValues["insurance_basis"]
        : null;
      $interestAmortized = in_array($formValues["interest_amortized"], $allowedUsedOptions, true)
        ? $formValues["interest_amortized"]
        : "Not Used";
      $serviceChargeAmortized = in_array($formValues["service_charge_amortized"], $allowedUsedOptions, true)
        ? $formValues["service_charge_amortized"]
        : "Not Used";

      if (!empty($_FILES["client_photo"]) && $_FILES["client_photo"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $photoFile = $_FILES["client_photo"];
        if ($photoFile["error"] !== UPLOAD_ERR_OK) {
          $errors[] = "Unable to upload the client photo.";
        } else {
          $maxPhotoSize = 5 * 1024 * 1024;
          if ($photoFile["size"] > $maxPhotoSize) {
            $errors[] = "Client photo must be 5MB or smaller.";
          } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($photoFile["tmp_name"]);
            $allowedTypes = [
              "image/jpeg" => "jpg",
              "image/png" => "png",
            ];
            if (!isset($allowedTypes[$mimeType])) {
              $errors[] = "Client photo must be a JPG or PNG image.";
            } else {
              $photoUpload = [
                "tmp_name" => $photoFile["tmp_name"],
                "extension" => $allowedTypes[$mimeType],
              ];
            }
          }
        }
      }

      $schedules = [];
      if (!empty($_POST["schedule"]) && is_array($_POST["schedule"])) {
        foreach ($_POST["schedule"] as $index => $row) {
          if (!is_array($row)) {
            continue;
          }
          $dueDate = trim((string) ($row["due_date"] ?? ""));
          if ($dueDate === "") {
            continue;
          }
          $scheduleDate = $normalizeDate($dueDate, "Schedule due date");
          $principal = $normalizeNumber($row["principal"] ?? "", "Schedule principal", true);
          $interest = $normalizeNumber($row["interest"] ?? "", "Schedule interest", true);
          $total = $normalizeNumber($row["total"] ?? "", "Schedule total", true);
          $balance = $normalizeNumber($row["balance"] ?? "", "Schedule balance", true);

          if ($scheduleDate !== null && $principal !== null && $interest !== null && $total !== null && $balance !== null) {
            $installment = $normalizeInt($row["installment_no"] ?? ($index + 1), "Schedule installment");
            $schedules[] = [
              "installment_no" => $installment ?? ($index + 1),
              "due_date" => $scheduleDate,
              "principal" => $principal,
              "interest" => $interest,
              "total" => $total,
              "balance" => $balance,
            ];
          }
        }
      }

      if (empty($errors) && $client !== null) {
        $clientPhotoPath = null;
        if ($photoUpload !== null) {
          $uploadsDir = dirname(__DIR__) . "/uploads/loan-applications";
          if (!is_dir($uploadsDir) && !mkdir($uploadsDir, 0755, true) && !is_dir($uploadsDir)) {
            $errors[] = "Unable to create the upload directory.";
          } else {
            $filename = sprintf(
              "loan-app-%s-%s.%s",
              date("Ymd-His"),
              bin2hex(random_bytes(4)),
              $photoUpload["extension"]
            );
            $targetPath = $uploadsDir . "/" . $filename;

            if (!move_uploaded_file($photoUpload["tmp_name"], $targetPath)) {
              $errors[] = "Unable to save the client photo.";
            } else {
              $clientPhotoPath = "uploads/loan-applications/" . $filename;
            }
          }
        }
      }

      if (empty($errors) && $client !== null) {
        $applicationId = $loanRepo->generateApplicationId();

        $loanRepo->createLoanApplication([
          "application_id" => $applicationId,
          "client_id" => (int) $client["id"],
          "product_id" => $productId,
          "requested_amount" => $requestedAmount,
          "monthly_income" => null,
          "employment_info" => null,
          "terms_months" => $termsMonths,
          "term_unit" => $termUnit,
          "term_fixed" => (int) $formValues["term_fixed"],
          "savings_account" => $normalizeText($formValues["savings_account"]),
          "collateral" => null,
          "guarantor" => null,
          "interest_rate" => $interestRate,
          "interest_type" => $interestType,
          "equal_principal" => (int) $formValues["equal_principal"],
          "release_date" => $releaseDate,
          "maturity_date" => $maturityDate,
          "deduction_interest" => $deductionInterest,
          "deduction_service_charge" => $deductionServiceCharge,
          "deduction_climbs" => $deductionClimbs,
          "deduction_notarial_fee" => $deductionNotarialFee,
          "total_deductions" => $totalDeductions,
          "net_proceeds" => $netProceeds,
          "amortization_days" => $amortizationDays,
          "principal_interval" => $principalInterval,
          "interval_adjustment" => $intervalAdjustment,
          "fixed_amortization" => $fixedAmortization,
          "irregular_amortization" => $irregularAmortization,
          "insurance_amount" => $insuranceAmount,
          "insurance_basis" => $insuranceBasis,
          "interest_amortized" => $interestAmortized,
          "service_charge_amortized" => $serviceChargeAmortized,
          "client_photo_path" => $clientPhotoPath,
          "status" => "Pending",
          "priority" => "Normal",
          "submitted_date" => date("Y-m-d"),
          "schedules" => $schedules,
        ]);

        header("Location: loan-application.php?created=1");
        exit;
      }
    }

    if ($action === "create_cacobem") {
      $postedValues = $_POST["cacobem"] ?? [];
      foreach ($defaultCacobemValues as $key => $value) {
        $cacobemFormValues[$key] = trim((string) ($postedValues[$key] ?? ""));
      }

      $allowedAuthSchedules = ["15th", "30th", "15/30th"];
      if (!in_array($cacobemFormValues["authorization_schedule"], $allowedAuthSchedules, true)) {
        $cacobemFormValues["authorization_schedule"] = "";
      }

      $allowedSecurity = ["Secured", "Unsecured"];
      if (!in_array($cacobemFormValues["action_security"], $allowedSecurity, true)) {
        $cacobemFormValues["action_security"] = "";
      }

      $allowedTermUnit = ["days", "years"];
      if (!in_array($cacobemFormValues["pn_term_unit"], $allowedTermUnit, true)) {
        $cacobemFormValues["pn_term_unit"] = "";
      }

      $borrowerName = trim($cacobemFormValues["borrower_name"]);
      if ($borrowerName === "") {
        $cacobemErrors[] = "Borrower name is required.";
      }

      $applicationDateRaw = trim($cacobemFormValues["application_date"]);
      $applicationDate = null;
      if ($applicationDateRaw === "") {
        $cacobemErrors[] = "Application date is required.";
      } else {
        $date = DateTime::createFromFormat("Y-m-d", $applicationDateRaw);
        if ($date === false || $date->format("Y-m-d") !== $applicationDateRaw) {
          $cacobemErrors[] = "Application date must be a valid date.";
        } else {
          $applicationDate = $applicationDateRaw;
        }
      }

      $amountApplied = null;
      $amountAppliedRaw = trim($cacobemFormValues["amount_applied"]);
      if ($amountAppliedRaw !== "") {
        $normalized = str_replace([",", " "], "", $amountAppliedRaw);
        if (!is_numeric($normalized)) {
          $cacobemErrors[] = "Amount applied must be a number.";
        } else {
          $amountApplied = $normalized;
        }
      }

      if (empty($cacobemErrors)) {
        $dataJson = json_encode($cacobemFormValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $clientId = null;
        if (!empty($_POST["cacobem_client_id"]) && ctype_digit((string) $_POST["cacobem_client_id"])) {
          $clientId = (int) $_POST["cacobem_client_id"];
        }

        $cacobemRepo->create([
          "client_id" => $clientId,
          "borrower_name" => $borrowerName !== "" ? $borrowerName : null,
          "application_date" => $applicationDate,
          "amount_applied" => $amountApplied,
          "data_json" => $dataJson ?: "{}",
        ]);

        header("Location: loan-application.php?cacobem_created=1");
        exit;
      }
    }
  }

  $loanAppSelected = $formValues["borrower_id"] !== "";
  $clientPickerValue = "";
  if ($loanAppSelected && $clientInfo["name"] !== "") {
    $clientPickerValue = $clientInfo["name"] . " (" . $formValues["borrower_id"] . ")";
  }
  $cacobemSuccess = isset($_GET["cacobem_created"]);

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="tw-mx-auto tw-max-w-4xl tw-rounded-[26px] tw-border tw-border-stroke tw-bg-surface tw-p-6 tw-shadow-card">
    <div class="tw-text-center tw-text-sm tw-font-semibold tw-tracking-wide tw-text-ink">
      LOAN APPLICATION / RELEASE
    </div>
    <div class="tw-mt-4 tw-grid tw-gap-6">
      <div>
        <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">LOAN APPLICATION</div>
        <div class="tw-mt-3 tw-grid tw-gap-4 tw-md:grid-cols-2">
          <div>
            <label>Loan Product</label>
            <select>
              <option value="">Select loan product</option>
              <?php foreach ($loanProducts as $product) : ?>
                <option value="<?php echo (int) $product["id"]; ?>">
                  <?php echo htmlspecialchars((string) $product["name"]); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Client Name</label>
            <input
              type="text"
              placeholder="Select client"
              list="client-picker-list"
              data-client-picker-input
              autocomplete="off"
              value="<?php echo htmlspecialchars($clientPickerValue); ?>"
            />
            <datalist id="client-picker-list" data-client-picker-list>
              <?php foreach ($clientPicklist as $client) : ?>
                <?php
                  $displayName = $client["name"] . " (" . $client["borrower_id"] . ")";
                ?>
                <option
                  value="<?php echo htmlspecialchars($displayName); ?>"
                  data-borrower-id="<?php echo htmlspecialchars($client["borrower_id"]); ?>"
                  data-name="<?php echo htmlspecialchars($client["name"]); ?>"
                  data-phone="<?php echo htmlspecialchars($client["phone"]); ?>"
                ></option>
              <?php endforeach; ?>
            </datalist>
          </div>
        </div>
      </div>
      <div>
        <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">RELEASE APPROVED LOANS</div>
        <div class="tw-mt-3 tw-grid tw-gap-4 tw-md:grid-cols-2">
          <div>
            <label>Client Name</label>
            <input type="text" placeholder="Select client" />
          </div>
          <div>
            <label>Release Reference</label>
            <input type="text" placeholder="Optional" />
          </div>
          <div class="tw-md:col-span-2">
            <button class="btn" type="button" data-cacobem-open>CACOBEM</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section
    class="tw-mx-auto tw-mt-6 tw-max-w-6xl tw-rounded-[18px] tw-border tw-border-stroke tw-bg-surface tw-p-5 tw-shadow-card loan-application-gate<?php echo $loanAppSelected ? "" : " loan-application-locked"; ?>"
    data-loan-application-gate
    data-client-selected="<?php echo $loanAppSelected ? "1" : "0"; ?>"
    data-selected-borrower-id="<?php echo htmlspecialchars($formValues["borrower_id"]); ?>"
  >
    <div class="loan-application-overlay" data-loan-application-overlay>
      <div class="loan-application-overlay-card">
        <div class="tw-text-sm tw-font-semibold tw-text-ink">Select a client to start a loan application.</div>
        <div class="tw-mt-1 tw-text-xs tw-text-muted">Use the Client Name field above to unlock this section.</div>
      </div>
    </div>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
      <div>
        <div class="tw-text-base tw-font-semibold tw-text-ink">Loan Application</div>
        <div class="tw-mt-1 tw-text-sm tw-text-muted">Select a client to open the application.</div>
      </div>
      <button class="btn" type="button" data-loan-application-open data-loan-application-action <?php echo $loanAppSelected ? "" : "disabled"; ?>>Open Application</button>
    </div>


  </section>


  <div
    id="loanApplicationModal"
    class="tw-fixed tw-inset-0 tw-z-50 tw-hidden"
    style="display: none;"
    data-modal-root
    data-loan-application-modal
    data-open-modal="<?php echo (!empty($errors) || isset($_GET["created"])) ? "1" : "0"; ?>"
  >
    <div class="tw-absolute tw-inset-0 tw-bg-slate-900/50" data-modal-overlay></div>
    <div class="tw-relative tw-mx-auto tw-my-10 tw-w-[94%] tw-max-w-6xl tw-rounded-2xl tw-bg-[#dfeff8] tw-shadow-2xl tw-border tw-border-slate-200">
      <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-200">
        <div class="tw-text-base tw-font-bold tw-tracking-wide">LOAN APPLICATION</div>
        <div class="tw-flex tw-gap-2">
          <button class="btn ghost" type="button" data-loan-application-action>Save Draft</button>
          <button class="btn" type="submit" form="loan-application-form" data-loan-application-action>Submit</button>
          <button class="btn ghost" type="button" data-modal-close>Close</button>
        </div>
      </div>
      <div class="tw-px-6 tw-pt-4">
        <div class="tw-flex tw-gap-2 tw-text-sm tw-font-semibold" data-tab-group="loan-application">
          <button type="button" class="tw-rounded-full tw-bg-accent-3 tw-px-4 tw-py-2 tw-text-ink" data-tab-target="general">General</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="amortization">Amortization</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="other-details">Other Details</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="summary">Summary</button>
        </div>
      </div>
      <div class="tw-px-6 tw-pb-6">
        <div class="tw-max-h-[75vh] tw-overflow-y-auto tw-pt-4">
          <form id="loan-application-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
            <input type="hidden" name="action" value="create_application" />

            <?php if (isset($_GET["created"])) : ?>
              <div class="form-error" style="background: #e9f9ef; border-color: #bde7cb; color: #1d5b3a;">
                Application submitted successfully.
              </div>
            <?php endif; ?>
            <?php if (!empty($errors)) : ?>
              <div class="form-error">
                <strong>Please review the errors below:</strong>
                <ul>
                  <?php foreach ($errors as $error) : ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <section class="tw-grid tw-gap-6 tw-lg:grid-cols-3" data-tab-panel="general">
              <div class="tw-rounded-[18px] tw-border tw-border-stroke tw-bg-surface tw-p-5 tw-shadow-card tw-lg:col-span-2">
                <div class="tw-mt-5 tw-grid tw-gap-4 tw-md:grid-cols-2">
                  <div>
                    <label>Loan Product</label>
                    <select name="product_id">
                      <option value="">Select loan product</option>
                      <?php foreach ($loanProducts as $product) : ?>
                        <option value="<?php echo (int) $product["id"]; ?>" <?php echo $formValues["product_id"] === (string) $product["id"] ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars((string) $product["name"]); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div>
                    <label>Client Name</label>
                    <input type="text" value="<?php echo htmlspecialchars($clientInfo["name"]); ?>" placeholder="Auto-filled" readonly data-client-name-field />
                  </div>
                  <div>
                    <label>Borrower ID</label>
                    <input type="text" name="borrower_id" placeholder="BR-000278" value="<?php echo htmlspecialchars($formValues["borrower_id"]); ?>" readonly data-borrower-id-field />
                  </div>
                  <div>
                    <label>CP Number</label>
                    <input type="text" value="<?php echo htmlspecialchars($clientInfo["phone"]); ?>" placeholder="Auto-filled" readonly data-client-phone-field />
                  </div>
                  <div>
                    <label>Savings Account</label>
                    <input type="text" name="savings_account" placeholder="Optional" value="<?php echo htmlspecialchars($formValues["savings_account"]); ?>" />
                  </div>
                  <div>
                    <label>Loan Amount</label>
                    <input type="text" name="requested_amount" placeholder="150,000" value="<?php echo htmlspecialchars($formValues["requested_amount"]); ?>" />
                  </div>
                  <div>
                    <label>Term / Term Unit</label>
                    <div class="tw-grid tw-gap-2 tw-grid-cols-[1fr_1.2fr]">
                      <input type="text" name="terms_months" placeholder="144" value="<?php echo htmlspecialchars($formValues["terms_months"]); ?>" />
                      <select name="term_unit">
                        <option value="">Select</option>
                        <?php foreach ($allowedTermUnits as $unit) : ?>
                          <option value="<?php echo htmlspecialchars($unit); ?>" <?php echo $formValues["term_unit"] === $unit ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($unit); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <label class="tw-mt-2 tw-flex tw-items-center tw-gap-2 tw-text-xs tw-text-muted">
                      <input type="checkbox" name="term_fixed" <?php echo $formValues["term_fixed"] ? "checked" : ""; ?> />
                      Fixed
                    </label>
                  </div>
                  <div>
                    <label>Interest Rate</label>
                    <div class="tw-grid tw-gap-2 tw-grid-cols-[1fr_1.2fr]">
                      <input type="text" name="interest_rate" placeholder="6.0000" value="<?php echo htmlspecialchars($formValues["interest_rate"]); ?>" />
                      <select name="interest_type">
                        <option value="">Select</option>
                        <?php foreach ($allowedInterestTypes as $type) : ?>
                          <option value="<?php echo htmlspecialchars($type); ?>" <?php echo $formValues["interest_type"] === $type ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($type); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <label class="tw-mt-2 tw-flex tw-items-center tw-gap-2 tw-text-xs tw-text-muted">
                      <input type="checkbox" name="equal_principal" <?php echo $formValues["equal_principal"] ? "checked" : ""; ?> />
                      Equal Principal
                    </label>
                  </div>
                  <div>
                    <label>Release Date</label>
                    <input type="date" name="release_date" value="<?php echo htmlspecialchars($formValues["release_date"]); ?>" />
                  </div>
                  <div>
                    <label>Maturity</label>
                    <input type="date" name="maturity_date" value="<?php echo htmlspecialchars($formValues["maturity_date"]); ?>" />
                  </div>
                </div>

                <div class="divider"></div>

                <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">DEDUCTIONS</div>
                <div class="tw-mt-3 tw-grid tw-gap-4 tw-md:grid-cols-2">
                  <div>
                    <label>Interest</label>
                    <input type="text" name="deduction_interest" value="<?php echo htmlspecialchars($formValues["deduction_interest"]); ?>" />
                  </div>
                  <div>
                    <label>Service Charge</label>
                    <input type="text" name="deduction_service_charge" value="<?php echo htmlspecialchars($formValues["deduction_service_charge"]); ?>" />
                  </div>
                  <div>
                    <label>Climbs (PHP)</label>
                    <input type="text" name="deduction_climbs" value="<?php echo htmlspecialchars($formValues["deduction_climbs"]); ?>" />
                  </div>
                  <div>
                    <label>Notarial Fee</label>
                    <input type="text" name="deduction_notarial_fee" value="<?php echo htmlspecialchars($formValues["deduction_notarial_fee"]); ?>" />
                  </div>
                  <div>
                    <label>Total Deductions</label>
                    <input type="text" name="total_deductions" value="<?php echo htmlspecialchars($formValues["total_deductions"]); ?>" />
                  </div>
                  <div>
                    <label>Net Proceeds</label>
                    <input type="text" name="net_proceeds" value="<?php echo htmlspecialchars($formValues["net_proceeds"]); ?>" />
                  </div>
                </div>
              </div>

              <div class="tw-rounded-[18px] tw-border tw-border-stroke tw-bg-surface tw-p-5 tw-shadow-card">
                <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">CLIENT PHOTO</div>
                <div class="tw-mt-3 tw-flex tw-h-48 tw-items-center tw-justify-center tw-rounded-xl tw-border tw-border-dashed tw-border-stroke tw-bg-surface-2 tw-text-sm tw-text-muted">
                  Upload a photo
                </div>
                <div class="tw-mt-4">
                  <label>Upload Photo</label>
                  <input type="file" name="client_photo" accept=".jpg,.jpeg,.png" />
                  <p class="tw-mt-2 tw-text-xs tw-text-muted">PNG or JPG. Up to 5MB.</p>
                </div>
              </div>
            </section>
            <section class="tw-grid tw-gap-6 tw-lg:grid-cols-3" data-tab-panel="amortization" style="display: none;">
              <div class="tw-rounded-[18px] tw-border tw-border-stroke tw-bg-surface tw-p-5 tw-shadow-card tw-lg:col-span-1">
                <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">AMORTIZATION OPTIONS</div>
                <div class="tw-mt-4 tw-grid tw-gap-4">
                  <div>
                    <label>Amortization Days</label>
                    <select name="amortization_days">
                      <option value="">Select</option>
                      <?php foreach ($allowedAmortizationDays as $option) : ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["amortization_days"] === $option ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars($option); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div>
                    <label>Principal Interval</label>
                    <select name="principal_interval">
                      <option value="">Select</option>
                      <?php foreach ($allowedPrincipalIntervals as $option) : ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["principal_interval"] === $option ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars($option); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div>
                    <label>Interval Adjustment</label>
                    <select name="interval_adjustment">
                      <option value="">Select</option>
                      <?php foreach ($allowedIntervalAdjustments as $option) : ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["interval_adjustment"] === $option ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars($option); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div>
                    <label>Fixed Amortization</label>
                    <input type="text" name="fixed_amortization" value="<?php echo htmlspecialchars($formValues["fixed_amortization"]); ?>" />
                  </div>
                  <div>
                    <label>Irregular Amortization</label>
                    <input type="text" name="irregular_amortization" value="<?php echo htmlspecialchars($formValues["irregular_amortization"]); ?>" />
                  </div>
                  <div>
                    <label>Insurance (PHP)</label>
                    <div class="tw-grid tw-gap-2 tw-grid-cols-[1fr_1fr]">
                      <input type="text" name="insurance_amount" value="<?php echo htmlspecialchars($formValues["insurance_amount"]); ?>" />
                      <select name="insurance_basis">
                        <?php foreach ($allowedInsuranceBasis as $basis) : ?>
                          <option value="<?php echo htmlspecialchars($basis); ?>" <?php echo $formValues["insurance_basis"] === $basis ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($basis); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="divider"></div>

                <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">PARTIAL DEDUCTIONS</div>
                <div class="tw-mt-4 tw-grid tw-gap-4">
                  <div>
                    <label>Interest Amortized</label>
                    <select name="interest_amortized">
                      <?php foreach ($allowedUsedOptions as $option) : ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["interest_amortized"] === $option ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars($option); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div>
                    <label>S.C. Amortized</label>
                    <select name="service_charge_amortized">
                      <?php foreach ($allowedUsedOptions as $option) : ?>
                        <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["service_charge_amortized"] === $option ? "selected" : ""; ?>>
                          <?php echo htmlspecialchars($option); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="tw-rounded-[18px] tw-border tw-border-stroke tw-bg-surface tw-p-5 tw-shadow-card tw-lg:col-span-2">
                <div class="tw-text-xs tw-font-semibold tw-tracking-wide tw-text-ink">AMORTIZATION SCHEDULE</div>
                <div class="table-wrap tw-mt-4">
                  <table class="data-table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Principal</th>
                        <th>Interest</th>
                        <th>Total</th>
                        <th>Balance</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="empty-row" colspan="6">No schedule generated yet.</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>

            <section data-tab-panel="other-details" style="display: none;">
              <div class="tw-rounded-[18px] tw-border tw-border-dashed tw-border-stroke tw-bg-surface tw-p-6 tw-text-center tw-text-sm tw-text-muted">
                No additional details added yet.
              </div>
            </section>

            <section data-tab-panel="summary" style="display: none;">
              <div class="tw-rounded-[18px] tw-border tw-border-dashed tw-border-stroke tw-bg-surface tw-p-6 tw-text-center tw-text-sm tw-text-muted">
                Summary will appear after you fill out the application.
              </div>
            </section>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div
    id="cacobemModal"
    class="tw-fixed tw-inset-0 tw-z-50 tw-hidden"
    style="display: none;"
    data-cacobem-modal
    data-open-modal="<?php echo (!empty($cacobemErrors) || $cacobemSuccess) ? "1" : "0"; ?>"
  >
    <div class="tw-absolute tw-inset-0 tw-bg-slate-900/50" data-cacobem-overlay></div>
    <div class="tw-relative tw-mx-auto tw-my-10 tw-w-[94%] tw-max-w-6xl tw-rounded-2xl tw-bg-[#f7f7f7] tw-shadow-2xl tw-border tw-border-slate-200">
      <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-200">
        <div class="tw-text-base tw-font-bold tw-tracking-wide">CACOBEM LOAN APPLICATION</div>
        <div class="tw-flex tw-gap-2">
          <button class="btn" type="submit" form="cacobem-form">Save CACOBEM</button>
          <button class="btn ghost" type="button" data-cacobem-close>Close</button>
        </div>
      </div>
      <div class="tw-px-6 tw-py-6">
        <form id="cacobem-form" method="post">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
          <input type="hidden" name="action" value="create_cacobem" />
          <input type="hidden" name="cacobem_client_id" value="" />

          <?php if ($cacobemSuccess) : ?>
            <div class="form-error" style="background: #e9f9ef; border-color: #bde7cb; color: #1d5b3a;">
              CACOBEM application saved successfully.
            </div>
          <?php endif; ?>

          <?php if (!empty($cacobemErrors)) : ?>
            <div class="form-error">
              <strong>Please review the errors below:</strong>
              <ul>
                <?php foreach ($cacobemErrors as $error) : ?>
                  <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="cacobem-doc">
            <?php
              $cacobemValues = $cacobemFormValues;
              $cacobemShowAuthorizationDuplicate = false;
              require "../partials/cacobem-page1.php";
            ?>
          </div>
        </form>
      </div>
    </div>
  </div>

</main>
<?php require "../partials/footer.php"; ?>

