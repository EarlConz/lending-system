
<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Settings";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Product Settings";
  $activePage = "settings-loan-products";

  $settingsRepo = new SettingsRepository();
  $productStats = $settingsRepo->getProductStats();

  $statusFilter = $_GET["status"] ?? "Active";
  if (!in_array($statusFilter, ["Active", "Inactive", "All"], true)) {
    $statusFilter = "Active";
  }
  $loanProducts = $statusFilter === "All"
    ? $settingsRepo->getLoanProducts(null)
    : $settingsRepo->getLoanProducts($statusFilter);

  $mode = $_GET["mode"] ?? "";
  if (!in_array($mode, ["add", "edit", "view"], true)) {
    $mode = "";
  }
  $isReadOnly = $mode === "view";

  $errors = [];
  $formValues = [
    "product_id" => "",
    "name" => "",
    "code" => "",
    "description" => "",
    "status" => "Active",
    "loan_type" => "Not Used",
    "promissory_note" => "Not Used",
    "max_loan_amount" => "",
    "ceiling_loan_product" => "",
    "max_loan_count" => "",
    "grouping" => "Not Used",
    "cost_center" => "Not Used",
    "borrower_type_default" => "Not Used",
    "require_security" => "Not Used",
    "default_security" => "Not Used",
    "proceeds_type_default" => "Not Used",
    "enable_deed_assignment" => "Not Used",
    "required_no_employees" => 0,
    "required_coborrower" => 0,
    "required_comakers" => "",
    "employee_loan" => "Not Used",
    "term_unit" => "Not Used",
    "term_unit_flexible" => 0,
    "fixed_number_days" => "",
    "fixed_number_days_flexible" => 0,
    "default_term" => "",
    "default_term_flexible" => 0,
    "maximum_term" => "",
    "interest_rate" => "0.00",
    "interest_rate_flexible" => 0,
    "recompute_interest" => 0,
    "interest_basis_computation" => "Not Used",
    "interest_basis_flexible" => 0,
    "interest_computation" => "Not Used",
    "interest_computation_flexible" => 0,
    "interest_rate_minimum" => "0.00",
    "days_in_year" => "360",
    "penalty_per_amort_fixed_rate" => "",
    "penalty_per_amort_fixed_amount" => "",
    "penalty_per_amort_running_rate" => "",
    "penalty_per_amort_grace_days" => "",
    "penalty_per_amort_basis" => "Not Used",
    "penalty_after_maturity_fixed_rate" => "",
    "penalty_after_maturity_fixed_amount" => "",
    "penalty_after_maturity_running_rate" => "",
    "penalty_after_maturity_grace_days" => "",
    "penalty_after_maturity_basis" => "Not Used",
    "disregard_payments_after_maturity" => 0,
    "include_amort_penalty" => 0,
    "past_due_interest_rate" => "",
    "past_due_interest_basis" => "Not Used",
    "past_due_disregard_payments" => 0,
    "penalty_gl_account" => "",
    "grace_period_option" => "Not Used",
    "secured_approval_min" => "",
    "secured_approval_max" => "",
    "secured_approver_count" => "1",
    "unsecured_approval_min" => "",
    "unsecured_approval_max" => "",
    "unsecured_approver_count" => "1",
    "service_charge_used" => "Not Used",
    "savings_discounted_used" => "Not Used",
    "grt_used" => "Not Used",
    "insurance_used" => "Not Used",
    "insurance_name" => "",
    "insurance_flexible" => 0,
    "insurance_provider_default" => "ICISP",
    "insurance_table" => "Not Used",
    "insurance_printing_form" => "Yes",
    "insurance_gl_account" => "",
    "insurance_product" => "None",
    "notarial_used" => "Not Used",
    "doc_stamp_used" => "Not Used",
    "inspection_fee_used" => "Not Used",
    "filing_fee_used" => "Not Used",
    "processing_fee_used" => "Not Used",
    "processing_fee_name" => "",
    "processing_fee_bracket_option" => "By Amount (PHP)",
    "processing_fee_rate_option" => "Percent (%)",
    "processing_fee_flexible" => 0,
    "processing_fee_gl_account" => "",
    "ctr_fund_used" => "Not Used",
    "insurance2_used" => "Not Used",
    "deduction8_used" => "Not Used",
    "deduction9_used" => "Not Used",
    "service_charge_amortized" => "Not Used",
    "savings_amortized" => "Not Used",
    "amort1" => "Not Used",
    "amort2" => "Not Used",
    "amort_date_adjustment" => "Not Used",
    "amort_adjust_on_holidays" => "Not Used",
    "amortization_grace_period" => "",
    "auto_debit_amortization" => 0,
    "savings_holdout_value" => "",
    "savings_holdout_basis" => "Percent",
    "cure_period_daily" => "0",
    "cure_period_weekly" => "0",
    "cure_period_semi_monthly" => "0",
    "cure_period_monthly" => "0",
    "cure_period_quarterly" => "0",
    "cure_period_semi_annual" => "0",
    "cure_period_annual" => "0",
    "cure_period_lumpsum" => "0",
    "enable_individual_cure_period" => 0,
    "enable_release_tagging" => 0,
    "cash_disbursed_by_teller" => 0,
    "security_dependent_pns" => 0,
    "acl_exempted" => 0,
    "acl_assessment" => "",
    "comakership_limit" => "",
    "collection_list_display" => "Not Used",
    "collection_list_orientation" => "Not Used",
    "balance_to_show" => "Not Used",
    "reflect_date_granted" => 0,
    "reflect_loan_amount" => 0,
    "reflect_savings_balance" => 0,
    "reflect_duedate" => 0,
    "signature_on_collection_list" => 0,
    "sms_language" => "Not Used",
    "sms_free" => "Not Used",
    "sms_show_unpaid_amorts" => 0,
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

  $normalizeInt = function ($value, string $label) use (&$errors): ?int {
    $value = trim((string) $value);
    if ($value === "") {
      return null;
    }
    if (!ctype_digit($value)) {
      $errors[] = $label . " must be a whole number.";
      return null;
    }
    return (int) $value;
  };

  $normalizeUsed = function ($value): string {
    return $value === "Used" ? "Used" : "Not Used";
  };

  $grtOptions = [
    "Not Used",
    "% of Total Interest and S.C.",
    "% of Total Interest Only",
    "% of Interest Discounted Only",
    "% of Interest Amortized Only",
    "% of Total S.C. Only",
    "% of Discounted S.C. Only",
    "% of Amortized S.C. Only",
    "% of Amortized Interest & S.C. Only",
    "% of Amortized Interest & Discounted S.C. Only",
  ];
  $insuranceProviderOptions = [
    "CBLI-MRI",
    "CBLI-KALINGA",
    "CBLI-NONLIFE",
    "CLIMBS-MRI",
    "ICISP",
    "INSURANCE",
  ];
  $insuranceTableOptions = ["Not Used", "Used"];
  $insurancePrintingOptions = ["Yes", "No"];
  $insuranceProductOptions = ["None"];
  $processingFeeBracketOptions = [
    "By Term (Days)",
    "By Amount (PHP)",
    "By Amount, pro-rated by term, 360 days",
    "By Amount, pro-rated by term, 365 days",
  ];
  $processingFeeRateOptions = ["Percent (%)", "Amount (PHP)"];

  $productId = null;
  if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $idParam = $_GET["id"] ?? "";
    if ($idParam !== "" && ctype_digit($idParam)) {
      $productId = (int) $idParam;
      $existing = $settingsRepo->getLoanProductById($productId);
      if ($existing) {
        foreach ($formValues as $key => $value) {
          if (array_key_exists($key, $existing)) {
            $formValues[$key] = $existing[$key];
          }
        }
        $formValues["product_id"] = (string) $productId;
      }
    }
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";
    $mode = $_POST["mode"] ?? $mode;
    if (!in_array($mode, ["add", "edit", "view"], true)) {
      $mode = "";
    }

    if ($action === "save_product") {
      if ($mode === "view") {
        $errors[] = "View mode does not allow saving.";
      }

      $formValues = array_merge($formValues, [
        "product_id" => trim((string) ($_POST["product_id"] ?? "")),
        "name" => trim((string) ($_POST["name"] ?? "")),
        "code" => trim((string) ($_POST["code"] ?? "")),
        "description" => trim((string) ($_POST["description"] ?? "")),
        "status" => trim((string) ($_POST["status"] ?? "Active")),
        "loan_type" => $normalizeUsed($_POST["loan_type"] ?? ""),
        "promissory_note" => $normalizeUsed($_POST["promissory_note"] ?? ""),
        "max_loan_amount" => trim((string) ($_POST["max_loan_amount"] ?? "")),
        "ceiling_loan_product" => trim((string) ($_POST["ceiling_loan_product"] ?? "")),
        "max_loan_count" => trim((string) ($_POST["max_loan_count"] ?? "")),
        "grouping" => $normalizeUsed($_POST["grouping"] ?? ""),
        "cost_center" => $normalizeUsed($_POST["cost_center"] ?? ""),
        "borrower_type_default" => $normalizeUsed($_POST["borrower_type_default"] ?? ""),
        "require_security" => $normalizeUsed($_POST["require_security"] ?? ""),
        "default_security" => $normalizeUsed($_POST["default_security"] ?? ""),
        "proceeds_type_default" => $normalizeUsed($_POST["proceeds_type_default"] ?? ""),
        "enable_deed_assignment" => $normalizeUsed($_POST["enable_deed_assignment"] ?? ""),
        "required_no_employees" => isset($_POST["required_no_employees"]) ? 1 : 0,
        "required_coborrower" => isset($_POST["required_coborrower"]) ? 1 : 0,
        "required_comakers" => trim((string) ($_POST["required_comakers"] ?? "")),
        "employee_loan" => $normalizeUsed($_POST["employee_loan"] ?? ""),
        "term_unit" => $normalizeUsed($_POST["term_unit"] ?? ""),
        "term_unit_flexible" => isset($_POST["term_unit_flexible"]) ? 1 : 0,
        "fixed_number_days" => trim((string) ($_POST["fixed_number_days"] ?? "")),
        "fixed_number_days_flexible" => isset($_POST["fixed_number_days_flexible"]) ? 1 : 0,
        "default_term" => trim((string) ($_POST["default_term"] ?? "")),
        "default_term_flexible" => isset($_POST["default_term_flexible"]) ? 1 : 0,
        "maximum_term" => trim((string) ($_POST["maximum_term"] ?? "")),
        "interest_rate" => trim((string) ($_POST["interest_rate"] ?? "0.00")),
        "interest_rate_flexible" => isset($_POST["interest_rate_flexible"]) ? 1 : 0,
        "recompute_interest" => isset($_POST["recompute_interest"]) ? 1 : 0,
        "interest_basis_computation" => $normalizeUsed($_POST["interest_basis_computation"] ?? ""),
        "interest_basis_flexible" => isset($_POST["interest_basis_flexible"]) ? 1 : 0,
        "interest_computation" => $normalizeUsed($_POST["interest_computation"] ?? ""),
        "interest_computation_flexible" => isset($_POST["interest_computation_flexible"]) ? 1 : 0,
        "interest_rate_minimum" => trim((string) ($_POST["interest_rate_minimum"] ?? "0.00")),
        "days_in_year" => trim((string) ($_POST["days_in_year"] ?? "360")),
        "penalty_per_amort_fixed_rate" => trim((string) ($_POST["penalty_per_amort_fixed_rate"] ?? "")),
        "penalty_per_amort_fixed_amount" => trim((string) ($_POST["penalty_per_amort_fixed_amount"] ?? "")),
        "penalty_per_amort_running_rate" => trim((string) ($_POST["penalty_per_amort_running_rate"] ?? "")),
        "penalty_per_amort_grace_days" => trim((string) ($_POST["penalty_per_amort_grace_days"] ?? "")),
        "penalty_per_amort_basis" => $normalizeUsed($_POST["penalty_per_amort_basis"] ?? ""),
        "penalty_after_maturity_fixed_rate" => trim((string) ($_POST["penalty_after_maturity_fixed_rate"] ?? "")),
        "penalty_after_maturity_fixed_amount" => trim((string) ($_POST["penalty_after_maturity_fixed_amount"] ?? "")),
        "penalty_after_maturity_running_rate" => trim((string) ($_POST["penalty_after_maturity_running_rate"] ?? "")),
        "penalty_after_maturity_grace_days" => trim((string) ($_POST["penalty_after_maturity_grace_days"] ?? "")),
        "penalty_after_maturity_basis" => $normalizeUsed($_POST["penalty_after_maturity_basis"] ?? ""),
        "disregard_payments_after_maturity" => isset($_POST["disregard_payments_after_maturity"]) ? 1 : 0,
        "include_amort_penalty" => isset($_POST["include_amort_penalty"]) ? 1 : 0,
        "past_due_interest_rate" => trim((string) ($_POST["past_due_interest_rate"] ?? "")),
        "past_due_interest_basis" => $normalizeUsed($_POST["past_due_interest_basis"] ?? ""),
        "past_due_disregard_payments" => isset($_POST["past_due_disregard_payments"]) ? 1 : 0,
        "penalty_gl_account" => trim((string) ($_POST["penalty_gl_account"] ?? "")),
        "grace_period_option" => $normalizeUsed($_POST["grace_period_option"] ?? ""),
        "secured_approval_min" => trim((string) ($_POST["secured_approval_min"] ?? "")),
        "secured_approval_max" => trim((string) ($_POST["secured_approval_max"] ?? "")),
        "secured_approver_count" => trim((string) ($_POST["secured_approver_count"] ?? "1")),
        "unsecured_approval_min" => trim((string) ($_POST["unsecured_approval_min"] ?? "")),
        "unsecured_approval_max" => trim((string) ($_POST["unsecured_approval_max"] ?? "")),
        "unsecured_approver_count" => trim((string) ($_POST["unsecured_approver_count"] ?? "1")),
        "service_charge_used" => $normalizeUsed($_POST["service_charge_used"] ?? ""),
        "savings_discounted_used" => $normalizeUsed($_POST["savings_discounted_used"] ?? ""),
        "grt_used" => trim((string) ($_POST["grt_used"] ?? "Not Used")),
        "insurance_used" => $normalizeUsed($_POST["insurance_used"] ?? ""),
        "insurance_name" => trim((string) ($_POST["insurance_name"] ?? "")),
        "insurance_flexible" => isset($_POST["insurance_flexible"]) ? 1 : 0,
        "insurance_provider_default" => trim((string) ($_POST["insurance_provider_default"] ?? "")),
        "insurance_table" => trim((string) ($_POST["insurance_table"] ?? "Not Used")),
        "insurance_printing_form" => trim((string) ($_POST["insurance_printing_form"] ?? "Yes")),
        "insurance_gl_account" => trim((string) ($_POST["insurance_gl_account"] ?? "")),
        "insurance_product" => trim((string) ($_POST["insurance_product"] ?? "None")),
        "notarial_used" => $normalizeUsed($_POST["notarial_used"] ?? ""),
        "doc_stamp_used" => $normalizeUsed($_POST["doc_stamp_used"] ?? ""),
        "inspection_fee_used" => $normalizeUsed($_POST["inspection_fee_used"] ?? ""),
        "filing_fee_used" => $normalizeUsed($_POST["filing_fee_used"] ?? ""),
        "processing_fee_used" => $normalizeUsed($_POST["processing_fee_used"] ?? ""),
        "processing_fee_name" => trim((string) ($_POST["processing_fee_name"] ?? "")),
        "processing_fee_bracket_option" => trim((string) ($_POST["processing_fee_bracket_option"] ?? "By Amount (PHP)")),
        "processing_fee_rate_option" => trim((string) ($_POST["processing_fee_rate_option"] ?? "Percent (%)")),
        "processing_fee_flexible" => isset($_POST["processing_fee_flexible"]) ? 1 : 0,
        "processing_fee_gl_account" => trim((string) ($_POST["processing_fee_gl_account"] ?? "")),
        "ctr_fund_used" => $normalizeUsed($_POST["ctr_fund_used"] ?? ""),
        "insurance2_used" => $normalizeUsed($_POST["insurance2_used"] ?? ""),
        "deduction8_used" => $normalizeUsed($_POST["deduction8_used"] ?? ""),
        "deduction9_used" => $normalizeUsed($_POST["deduction9_used"] ?? ""),
        "service_charge_amortized" => $normalizeUsed($_POST["service_charge_amortized"] ?? ""),
        "savings_amortized" => $normalizeUsed($_POST["savings_amortized"] ?? ""),
        "amort1" => $normalizeUsed($_POST["amort1"] ?? ""),
        "amort2" => $normalizeUsed($_POST["amort2"] ?? ""),
        "amort_date_adjustment" => $normalizeUsed($_POST["amort_date_adjustment"] ?? ""),
        "amort_adjust_on_holidays" => $normalizeUsed($_POST["amort_adjust_on_holidays"] ?? ""),
        "amortization_grace_period" => trim((string) ($_POST["amortization_grace_period"] ?? "")),
        "auto_debit_amortization" => isset($_POST["auto_debit_amortization"]) ? 1 : 0,
        "savings_holdout_value" => trim((string) ($_POST["savings_holdout_value"] ?? "")),
        "savings_holdout_basis" => trim((string) ($_POST["savings_holdout_basis"] ?? "Percent")),
        "cure_period_daily" => trim((string) ($_POST["cure_period_daily"] ?? "0")),
        "cure_period_weekly" => trim((string) ($_POST["cure_period_weekly"] ?? "0")),
        "cure_period_semi_monthly" => trim((string) ($_POST["cure_period_semi_monthly"] ?? "0")),
        "cure_period_monthly" => trim((string) ($_POST["cure_period_monthly"] ?? "0")),
        "cure_period_quarterly" => trim((string) ($_POST["cure_period_quarterly"] ?? "0")),
        "cure_period_semi_annual" => trim((string) ($_POST["cure_period_semi_annual"] ?? "0")),
        "cure_period_annual" => trim((string) ($_POST["cure_period_annual"] ?? "0")),
        "cure_period_lumpsum" => trim((string) ($_POST["cure_period_lumpsum"] ?? "0")),
        "enable_individual_cure_period" => isset($_POST["enable_individual_cure_period"]) ? 1 : 0,
        "enable_release_tagging" => isset($_POST["enable_release_tagging"]) ? 1 : 0,
        "cash_disbursed_by_teller" => isset($_POST["cash_disbursed_by_teller"]) ? 1 : 0,
        "security_dependent_pns" => isset($_POST["security_dependent_pns"]) ? 1 : 0,
        "acl_exempted" => isset($_POST["acl_exempted"]) ? 1 : 0,
        "acl_assessment" => trim((string) ($_POST["acl_assessment"] ?? "")),
        "comakership_limit" => trim((string) ($_POST["comakership_limit"] ?? "")),
        "collection_list_display" => $normalizeUsed($_POST["collection_list_display"] ?? ""),
        "collection_list_orientation" => $normalizeUsed($_POST["collection_list_orientation"] ?? ""),
        "balance_to_show" => $normalizeUsed($_POST["balance_to_show"] ?? ""),
        "reflect_date_granted" => isset($_POST["reflect_date_granted"]) ? 1 : 0,
        "reflect_loan_amount" => isset($_POST["reflect_loan_amount"]) ? 1 : 0,
        "reflect_savings_balance" => isset($_POST["reflect_savings_balance"]) ? 1 : 0,
        "reflect_duedate" => isset($_POST["reflect_duedate"]) ? 1 : 0,
        "signature_on_collection_list" => isset($_POST["signature_on_collection_list"]) ? 1 : 0,
        "sms_language" => $normalizeUsed($_POST["sms_language"] ?? ""),
        "sms_free" => $normalizeUsed($_POST["sms_free"] ?? ""),
        "sms_show_unpaid_amorts" => isset($_POST["sms_show_unpaid_amorts"]) ? 1 : 0,
      ]);

      if ($formValues["name"] === "") {
        $errors[] = "Product name is required.";
      }

      $allowedStatus = ["Active", "Inactive"];
      if (!in_array($formValues["status"], $allowedStatus, true)) {
        $formValues["status"] = "Active";
      }

      $allowedDays = ["360", "365"];
      if (!in_array($formValues["days_in_year"], $allowedDays, true)) {
        $formValues["days_in_year"] = "360";
      }

      if (!in_array($formValues["grt_used"], $grtOptions, true)) {
        $formValues["grt_used"] = "Not Used";
      }

      if (!in_array($formValues["insurance_provider_default"], $insuranceProviderOptions, true)) {
        $formValues["insurance_provider_default"] = "ICISP";
      }

      if (!in_array($formValues["insurance_table"], $insuranceTableOptions, true)) {
        $formValues["insurance_table"] = "Not Used";
      }

      if (!in_array($formValues["insurance_printing_form"], $insurancePrintingOptions, true)) {
        $formValues["insurance_printing_form"] = "Yes";
      }

      if (!in_array($formValues["insurance_product"], $insuranceProductOptions, true)) {
        $formValues["insurance_product"] = "None";
      }

      if (!in_array($formValues["processing_fee_bracket_option"], $processingFeeBracketOptions, true)) {
        $formValues["processing_fee_bracket_option"] = "By Amount (PHP)";
      }

      if (!in_array($formValues["processing_fee_rate_option"], $processingFeeRateOptions, true)) {
        $formValues["processing_fee_rate_option"] = "Percent (%)";
      }

      $payload = [
        "name" => $formValues["name"],
        "code" => $normalizeText($formValues["code"]),
        "description" => $normalizeText($formValues["description"]),
        "status" => $formValues["status"],
        "loan_type" => $formValues["loan_type"],
        "promissory_note" => $formValues["promissory_note"],
        "max_loan_amount" => $normalizeNumber($formValues["max_loan_amount"], "Maximum loan amount"),
        "ceiling_loan_product" => $normalizeNumber($formValues["ceiling_loan_product"], "Ceiling of loan product"),
        "max_loan_count" => $normalizeInt($formValues["max_loan_count"], "Maximum loan count"),
        "grouping" => $formValues["grouping"],
        "cost_center" => $formValues["cost_center"],
        "borrower_type_default" => $formValues["borrower_type_default"],
        "require_security" => $formValues["require_security"],
        "default_security" => $formValues["default_security"],
        "proceeds_type_default" => $formValues["proceeds_type_default"],
        "enable_deed_assignment" => $formValues["enable_deed_assignment"],
        "required_no_employees" => $formValues["required_no_employees"],
        "required_coborrower" => $formValues["required_coborrower"],
        "required_comakers" => $normalizeInt($formValues["required_comakers"], "Comaker/s"),
        "employee_loan" => $formValues["employee_loan"],
        "term_unit" => $formValues["term_unit"],
        "term_unit_flexible" => $formValues["term_unit_flexible"],
        "fixed_number_days" => $normalizeInt($formValues["fixed_number_days"], "Fixed number of days"),
        "fixed_number_days_flexible" => $formValues["fixed_number_days_flexible"],
        "default_term" => $normalizeInt($formValues["default_term"], "Default term"),
        "default_term_flexible" => $formValues["default_term_flexible"],
        "maximum_term" => $normalizeInt($formValues["maximum_term"], "Maximum term"),
        "interest_rate" => $normalizeNumber($formValues["interest_rate"], "Interest rate") ?? "0.00",
        "interest_rate_flexible" => $formValues["interest_rate_flexible"],
        "recompute_interest" => $formValues["recompute_interest"],
        "interest_basis_computation" => $formValues["interest_basis_computation"],
        "interest_basis_flexible" => $formValues["interest_basis_flexible"],
        "interest_computation" => $formValues["interest_computation"],
        "interest_computation_flexible" => $formValues["interest_computation_flexible"],
        "interest_rate_minimum" => $normalizeNumber($formValues["interest_rate_minimum"], "Interest rate minimum") ?? "0.00",
        "days_in_year" => (int) $formValues["days_in_year"],
        "penalty_per_amort_fixed_rate" => $normalizeNumber($formValues["penalty_per_amort_fixed_rate"], "Penalty per amortization fixed rate"),
        "penalty_per_amort_fixed_amount" => $normalizeNumber($formValues["penalty_per_amort_fixed_amount"], "Penalty per amortization fixed amount"),
        "penalty_per_amort_running_rate" => $normalizeNumber($formValues["penalty_per_amort_running_rate"], "Penalty per amortization running rate"),
        "penalty_per_amort_grace_days" => $normalizeInt($formValues["penalty_per_amort_grace_days"], "Penalty per amortization grace period"),
        "penalty_per_amort_basis" => $formValues["penalty_per_amort_basis"],
        "penalty_after_maturity_fixed_rate" => $normalizeNumber($formValues["penalty_after_maturity_fixed_rate"], "Penalty after maturity fixed rate"),
        "penalty_after_maturity_fixed_amount" => $normalizeNumber($formValues["penalty_after_maturity_fixed_amount"], "Penalty after maturity fixed amount"),
        "penalty_after_maturity_running_rate" => $normalizeNumber($formValues["penalty_after_maturity_running_rate"], "Penalty after maturity running rate"),
        "penalty_after_maturity_grace_days" => $normalizeInt($formValues["penalty_after_maturity_grace_days"], "Penalty after maturity grace period"),
        "penalty_after_maturity_basis" => $formValues["penalty_after_maturity_basis"],
        "disregard_payments_after_maturity" => $formValues["disregard_payments_after_maturity"],
        "include_amort_penalty" => $formValues["include_amort_penalty"],
        "past_due_interest_rate" => $normalizeNumber($formValues["past_due_interest_rate"], "Past due interest rate"),
        "past_due_interest_basis" => $formValues["past_due_interest_basis"],
        "past_due_disregard_payments" => $formValues["past_due_disregard_payments"],
        "penalty_gl_account" => $normalizeText($formValues["penalty_gl_account"]),
        "grace_period_option" => $formValues["grace_period_option"],
        "secured_approval_min" => $normalizeNumber($formValues["secured_approval_min"], "Secured approval minimum"),
        "secured_approval_max" => $normalizeNumber($formValues["secured_approval_max"], "Secured approval maximum"),
        "secured_approver_count" => $normalizeInt($formValues["secured_approver_count"], "Secured approver count") ?? 1,
        "unsecured_approval_min" => $normalizeNumber($formValues["unsecured_approval_min"], "Unsecured approval minimum"),
        "unsecured_approval_max" => $normalizeNumber($formValues["unsecured_approval_max"], "Unsecured approval maximum"),
        "unsecured_approver_count" => $normalizeInt($formValues["unsecured_approver_count"], "Unsecured approver count") ?? 1,
        "service_charge_used" => $formValues["service_charge_used"],
        "savings_discounted_used" => $formValues["savings_discounted_used"],
        "grt_used" => $formValues["grt_used"],
        "insurance_used" => $formValues["insurance_used"],
        "insurance_name" => $normalizeText($formValues["insurance_name"]),
        "insurance_flexible" => $formValues["insurance_flexible"],
        "insurance_provider_default" => $formValues["insurance_provider_default"],
        "insurance_table" => $formValues["insurance_table"],
        "insurance_printing_form" => $formValues["insurance_printing_form"],
        "insurance_gl_account" => $normalizeText($formValues["insurance_gl_account"]),
        "insurance_product" => $formValues["insurance_product"],
        "notarial_used" => $formValues["notarial_used"],
        "doc_stamp_used" => $formValues["doc_stamp_used"],
        "inspection_fee_used" => $formValues["inspection_fee_used"],
        "filing_fee_used" => $formValues["filing_fee_used"],
        "processing_fee_used" => $formValues["processing_fee_used"],
        "processing_fee_name" => $normalizeText($formValues["processing_fee_name"]),
        "processing_fee_bracket_option" => $formValues["processing_fee_bracket_option"],
        "processing_fee_rate_option" => $formValues["processing_fee_rate_option"],
        "processing_fee_flexible" => $formValues["processing_fee_flexible"],
        "processing_fee_gl_account" => $normalizeText($formValues["processing_fee_gl_account"]),
        "ctr_fund_used" => $formValues["ctr_fund_used"],
        "insurance2_used" => $formValues["insurance2_used"],
        "deduction8_used" => $formValues["deduction8_used"],
        "deduction9_used" => $formValues["deduction9_used"],
        "service_charge_amortized" => $formValues["service_charge_amortized"],
        "savings_amortized" => $formValues["savings_amortized"],
        "amort1" => $formValues["amort1"],
        "amort2" => $formValues["amort2"],
        "amort_date_adjustment" => $formValues["amort_date_adjustment"],
        "amort_adjust_on_holidays" => $formValues["amort_adjust_on_holidays"],
        "amortization_grace_period" => $normalizeInt($formValues["amortization_grace_period"], "Amortization grace period"),
        "auto_debit_amortization" => $formValues["auto_debit_amortization"],
        "savings_holdout_value" => $normalizeNumber($formValues["savings_holdout_value"], "Savings holdout"),
        "savings_holdout_basis" => $formValues["savings_holdout_basis"],
        "cure_period_daily" => $normalizeInt($formValues["cure_period_daily"], "Daily cure period") ?? 0,
        "cure_period_weekly" => $normalizeInt($formValues["cure_period_weekly"], "Weekly cure period") ?? 0,
        "cure_period_semi_monthly" => $normalizeInt($formValues["cure_period_semi_monthly"], "Semi-monthly cure period") ?? 0,
        "cure_period_monthly" => $normalizeInt($formValues["cure_period_monthly"], "Monthly cure period") ?? 0,
        "cure_period_quarterly" => $normalizeInt($formValues["cure_period_quarterly"], "Quarterly cure period") ?? 0,
        "cure_period_semi_annual" => $normalizeInt($formValues["cure_period_semi_annual"], "Semi-annual cure period") ?? 0,
        "cure_period_annual" => $normalizeInt($formValues["cure_period_annual"], "Annual cure period") ?? 0,
        "cure_period_lumpsum" => $normalizeInt($formValues["cure_period_lumpsum"], "Lumpsum cure period") ?? 0,
        "enable_individual_cure_period" => $formValues["enable_individual_cure_period"],
        "enable_release_tagging" => $formValues["enable_release_tagging"],
        "cash_disbursed_by_teller" => $formValues["cash_disbursed_by_teller"],
        "security_dependent_pns" => $formValues["security_dependent_pns"],
        "acl_exempted" => $formValues["acl_exempted"],
        "acl_assessment" => $normalizeNumber($formValues["acl_assessment"], "ACL assessment"),
        "comakership_limit" => $normalizeInt($formValues["comakership_limit"], "Comakership limit"),
        "collection_list_display" => $formValues["collection_list_display"],
        "collection_list_orientation" => $formValues["collection_list_orientation"],
        "balance_to_show" => $formValues["balance_to_show"],
        "reflect_date_granted" => $formValues["reflect_date_granted"],
        "reflect_loan_amount" => $formValues["reflect_loan_amount"],
        "reflect_savings_balance" => $formValues["reflect_savings_balance"],
        "reflect_duedate" => $formValues["reflect_duedate"],
        "signature_on_collection_list" => $formValues["signature_on_collection_list"],
        "sms_language" => $formValues["sms_language"],
        "sms_free" => $formValues["sms_free"],
        "sms_show_unpaid_amorts" => $formValues["sms_show_unpaid_amorts"],
        "service_charge" => "0.00",
      ];

      if (empty($errors)) {
        $productId = $formValues["product_id"];
        if ($mode === "edit" && $productId !== "" && ctype_digit($productId)) {
          $settingsRepo->updateLoanProduct((int) $productId, $payload);
          header("Location: settings-loan-products.php?id=" . $productId);
          exit;
        }

        if ($mode === "add" || $mode === "") {
          $newId = $settingsRepo->createLoanProduct($payload);
          header("Location: settings-loan-products.php?id=" . $newId);
          exit;
        }

        header("Location: settings-loan-products.php");
        exit;
      }
    }
  }

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Manage loan products, rates, and service charges.</h2>
    <p>Keep your lending portfolio competitive with structured updates.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $productStats["active"]; ?></strong>
        <span>Active products</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["pending_updates"]; ?></strong>
        <span>Pending updates</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["draft"]; ?></strong>
        <span>Draft</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["archived"]; ?></strong>
        <span>Archived</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Product Management</h3>
      <div style="display: flex; gap: 12px; align-items: center;">
        <div class="tag-row" data-status-filter>
          <label class="radio-option">
            <input type="checkbox" data-status-option="Active" <?php echo in_array($statusFilter, ["Active", "All"], true) ? "checked" : ""; ?> />
            Active
          </label>
          <label class="radio-option">
            <input type="checkbox" data-status-option="Inactive" <?php echo in_array($statusFilter, ["Inactive", "All"], true) ? "checked" : ""; ?> />
            Deactivated
          </label>
        </div>
        <a class="btn" href="settings-loan-products.php?mode=add&status=<?php echo htmlspecialchars($statusFilter); ?>">Add</a>
      </div>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Product Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($loanProducts)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No loan products available.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($loanProducts as $index => $product) : ?>
              <?php $id = (string) ($product["id"] ?? ""); ?>
              <tr>
                <td><?php echo $index + 1; ?></td>
                <td><?php echo htmlspecialchars((string) $product["name"]); ?></td>
                <td><?php echo htmlspecialchars((string) ($product["code"] ?? "")); ?></td>
                <td><?php echo htmlspecialchars((string) ($product["description"] ?? "")); ?></td>
                <td><?php echo htmlspecialchars((string) $product["status"]); ?></td>
                <td class="tag-row">
                  <a class="btn small ghost" href="settings-loan-products.php?mode=view&id=<?php echo $id; ?>&status=<?php echo htmlspecialchars($statusFilter); ?>">View</a>
                  <a class="btn small" href="settings-loan-products.php?mode=edit&id=<?php echo $id; ?>&status=<?php echo htmlspecialchars($statusFilter); ?>">Edit</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <div id="loanProductModal" class="tw-fixed tw-inset-0 tw-z-50 tw-hidden" style="display: none;" data-modal-root data-open-modal="<?php echo $mode !== "" ? "1" : "0"; ?>">
    <div class="tw-absolute tw-inset-0 tw-bg-slate-900/50" data-modal-overlay></div>
    <div class="tw-relative tw-mx-auto tw-my-10 tw-w-[94%] tw-max-w-5xl tw-rounded-2xl tw-bg-[#dfeff8] tw-shadow-2xl tw-border tw-border-slate-200">
      <div class="tw-flex tw-items-center tw-justify-between tw-px-6 tw-py-4 tw-border-b tw-border-slate-200">
        <div class="tw-text-base tw-font-bold tw-tracking-wide">LOAN PRODUCT MGMT -</div>
        <div class="tw-flex tw-gap-2">
          <?php if (!$isReadOnly) : ?>
            <button class="btn" type="submit" form="loanProductForm">Submit</button>
          <?php endif; ?>
          <button class="btn ghost" type="button" data-modal-close>Back</button>
        </div>
      </div>
      <div class="tw-px-6 tw-pt-4">
        <div class="tw-flex tw-gap-2 tw-text-sm tw-font-semibold" data-tab-group="loan-product">
          <button type="button" class="tw-rounded-full tw-bg-accent-3 tw-px-4 tw-py-2 tw-text-ink" data-tab-target="general">General</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="rates">Rates / Limits</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="deductions">Deductions</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="amortization">Amortization</button>
          <button type="button" class="tw-rounded-full tw-bg-surface-2 tw-px-4 tw-py-2 tw-text-muted" data-tab-target="others">Others</button>
        </div>
      </div>
      <div class="tw-px-6 tw-pb-6">
        <div class="tw-max-h-[75vh] tw-overflow-y-auto tw-pt-4">
          <form id="loanProductForm" method="post" class="grid">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
      <input type="hidden" name="action" value="save_product" />
      <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode === "" ? "add" : $mode); ?>" />
      <input type="hidden" name="product_id" value="<?php echo htmlspecialchars((string) $formValues["product_id"]); ?>" />

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

      <?php if ($isReadOnly) : ?>
        <fieldset disabled>
      <?php endif; ?>

      <section class="card" data-tab-panel="general">
        <div class="section-title">
          <h3>General</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars((string) $formValues["name"]); ?>" />
          </div>
          <div>
            <label>Code</label>
            <input type="text" name="code" value="<?php echo htmlspecialchars((string) $formValues["code"]); ?>" />
          </div>
          <div style="grid-column: 1 / -1;">
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars((string) $formValues["description"]); ?>" />
          </div>
          <div>
            <label>Status</label>
            <select name="status">
              <option value="Active" <?php echo $formValues["status"] === "Active" ? "selected" : ""; ?>>Active</option>
              <option value="Inactive" <?php echo $formValues["status"] === "Inactive" ? "selected" : ""; ?>>Inactive</option>
            </select>
          </div>
          <div>
            <label>Loan Type</label>
            <select name="loan_type" data-used-toggle="loan_type">
              <option value="Not Used" <?php echo $formValues["loan_type"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["loan_type"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="loan_type">Placeholder for loan type settings.</div>
          </div>
          <div>
            <label>Promissory Note</label>
            <select name="promissory_note" data-used-toggle="promissory_note">
              <option value="Not Used" <?php echo $formValues["promissory_note"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["promissory_note"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="promissory_note">Placeholder for promissory note settings.</div>
          </div>
          <div>
            <label>Maximum Loan Amount</label>
            <input type="text" name="max_loan_amount" value="<?php echo htmlspecialchars((string) $formValues["max_loan_amount"]); ?>" />
          </div>
          <div>
            <label>Ceiling of Loan Product</label>
            <input type="text" name="ceiling_loan_product" value="<?php echo htmlspecialchars((string) $formValues["ceiling_loan_product"]); ?>" />
          </div>
          <div>
            <label>Maximum Loan Count</label>
            <input type="text" name="max_loan_count" value="<?php echo htmlspecialchars((string) $formValues["max_loan_count"]); ?>" />
          </div>
          <div>
            <label>Grouping</label>
            <select name="grouping" data-used-toggle="grouping">
              <option value="Not Used" <?php echo $formValues["grouping"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["grouping"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="grouping">Placeholder for grouping settings.</div>
          </div>
          <div>
            <label>Cost Center</label>
            <select name="cost_center" data-used-toggle="cost_center">
              <option value="Not Used" <?php echo $formValues["cost_center"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["cost_center"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="cost_center">Placeholder for cost center settings.</div>
          </div>
          <div>
            <label>Borrower Type Default</label>
            <select name="borrower_type_default" data-used-toggle="borrower_type_default">
              <option value="Not Used" <?php echo $formValues["borrower_type_default"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["borrower_type_default"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="borrower_type_default">Placeholder for borrower type settings.</div>
          </div>
          <div>
            <label>Require Security</label>
            <select name="require_security" data-used-toggle="require_security">
              <option value="Not Used" <?php echo $formValues["require_security"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["require_security"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="require_security">Placeholder for security requirement.</div>
          </div>
          <div>
            <label>Default Security</label>
            <select name="default_security" data-used-toggle="default_security">
              <option value="Not Used" <?php echo $formValues["default_security"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["default_security"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="default_security">Placeholder for default security settings.</div>
          </div>
          <div>
            <label>Proceeds Type Default</label>
            <select name="proceeds_type_default" data-used-toggle="proceeds_type_default">
              <option value="Not Used" <?php echo $formValues["proceeds_type_default"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["proceeds_type_default"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="proceeds_type_default">Placeholder for proceeds settings.</div>
          </div>
          <div style="grid-column: 1 / -1;">
            <label>Enable Deed of Assignment</label>
            <select name="enable_deed_assignment" data-used-toggle="enable_deed_assignment">
              <option value="Not Used" <?php echo $formValues["enable_deed_assignment"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["enable_deed_assignment"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="enable_deed_assignment">Placeholder for deed of assignment settings.</div>
          </div>
          <div style="grid-column: 1 / -1;">
            <label>Required Fields</label>
            <div class="radio-group">
              <label class="radio-option">
                <input type="checkbox" name="required_no_employees" <?php echo $formValues["required_no_employees"] ? "checked" : ""; ?> />
                No. of Employees
              </label>
              <label class="radio-option">
                <input type="checkbox" name="required_coborrower" <?php echo $formValues["required_coborrower"] ? "checked" : ""; ?> />
                Co-Borrower
              </label>
              <label class="radio-option">
                <span>Comaker/s</span>
                <input type="text" name="required_comakers" style="width: 70px;" value="<?php echo htmlspecialchars((string) $formValues["required_comakers"]); ?>" />
              </label>
            </div>
          </div>
          <div>
            <label>Employee Loan</label>
            <select name="employee_loan" data-used-toggle="employee_loan">
              <option value="Not Used" <?php echo $formValues["employee_loan"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["employee_loan"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="employee_loan">Placeholder for employee loan settings.</div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Term</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Term Unit</label>
            <select name="term_unit" data-used-toggle="term_unit">
              <option value="Not Used" <?php echo $formValues["term_unit"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["term_unit"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="term_unit">Placeholder for term unit settings.</div>
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="term_unit_flexible" <?php echo $formValues["term_unit_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Fixed Number of Days</label>
            <input type="text" name="fixed_number_days" value="<?php echo htmlspecialchars((string) $formValues["fixed_number_days"]); ?>" />
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="fixed_number_days_flexible" <?php echo $formValues["fixed_number_days_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Default Term</label>
            <input type="text" name="default_term" value="<?php echo htmlspecialchars((string) $formValues["default_term"]); ?>" />
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="default_term_flexible" <?php echo $formValues["default_term_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Maximum Term</label>
            <input type="text" name="maximum_term" value="<?php echo htmlspecialchars((string) $formValues["maximum_term"]); ?>" />
          </div>
        </div>
      </section>

      <section class="card" data-tab-panel="rates" style="display: none;">
        <div class="section-title">
          <h3>Rates / Limits</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Interest Rate (%)</label>
            <input type="text" name="interest_rate" value="<?php echo htmlspecialchars((string) $formValues["interest_rate"]); ?>" />
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="interest_rate_flexible" <?php echo $formValues["interest_rate_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Recompute Interest</label>
            <input type="checkbox" name="recompute_interest" <?php echo $formValues["recompute_interest"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Interest Basis Computation</label>
            <select name="interest_basis_computation" data-used-toggle="interest_basis_computation">
              <option value="Not Used" <?php echo $formValues["interest_basis_computation"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["interest_basis_computation"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="interest_basis_computation">Placeholder for interest basis settings.</div>
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="interest_basis_flexible" <?php echo $formValues["interest_basis_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Interest Computation</label>
            <select name="interest_computation" data-used-toggle="interest_computation">
              <option value="Not Used" <?php echo $formValues["interest_computation"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["interest_computation"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="interest_computation">Placeholder for interest computation settings.</div>
          </div>
          <div>
            <label>Flexible</label>
            <input type="checkbox" name="interest_computation_flexible" <?php echo $formValues["interest_computation_flexible"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Interest Rate Minimum (%)</label>
            <input type="text" name="interest_rate_minimum" value="<?php echo htmlspecialchars((string) $formValues["interest_rate_minimum"]); ?>" />
          </div>
          <div>
            <label>Days in a Year</label>
            <div class="radio-group">
              <label class="radio-option">
                <input type="radio" name="days_in_year" value="360" <?php echo $formValues["days_in_year"] === "360" ? "checked" : ""; ?> />
                360
              </label>
              <label class="radio-option">
                <input type="radio" name="days_in_year" value="365" <?php echo $formValues["days_in_year"] === "365" ? "checked" : ""; ?> />
                365
              </label>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Penalty</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Penalty Per Amort'n Fixed Rate (%)</label>
            <input type="text" name="penalty_per_amort_fixed_rate" value="<?php echo htmlspecialchars((string) $formValues["penalty_per_amort_fixed_rate"]); ?>" />
          </div>
          <div>
            <label>Penalty Per Amort'n Fixed Amount</label>
            <input type="text" name="penalty_per_amort_fixed_amount" value="<?php echo htmlspecialchars((string) $formValues["penalty_per_amort_fixed_amount"]); ?>" />
          </div>
          <div>
            <label>Penalty Per Amort'n Running Rate (%)</label>
            <input type="text" name="penalty_per_amort_running_rate" value="<?php echo htmlspecialchars((string) $formValues["penalty_per_amort_running_rate"]); ?>" />
          </div>
          <div>
            <label>Penalty Per Amort'n Grace Period (Days)</label>
            <input type="text" name="penalty_per_amort_grace_days" value="<?php echo htmlspecialchars((string) $formValues["penalty_per_amort_grace_days"]); ?>" />
          </div>
          <div>
            <label>Penalty Per Amort'n Basis</label>
            <select name="penalty_per_amort_basis" data-used-toggle="penalty_per_amort_basis">
              <option value="Not Used" <?php echo $formValues["penalty_per_amort_basis"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["penalty_per_amort_basis"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="penalty_per_amort_basis">Placeholder for penalty computation basis.</div>
          </div>
        </div>

        <div class="form-grid" style="margin-top: 12px;">
          <div>
            <label>Penalty After Maturity Fixed Rate (%)</label>
            <input type="text" name="penalty_after_maturity_fixed_rate" value="<?php echo htmlspecialchars((string) $formValues["penalty_after_maturity_fixed_rate"]); ?>" />
          </div>
          <div>
            <label>Penalty After Maturity Fixed Amount</label>
            <input type="text" name="penalty_after_maturity_fixed_amount" value="<?php echo htmlspecialchars((string) $formValues["penalty_after_maturity_fixed_amount"]); ?>" />
          </div>
          <div>
            <label>Penalty After Maturity Running Rate (%)</label>
            <input type="text" name="penalty_after_maturity_running_rate" value="<?php echo htmlspecialchars((string) $formValues["penalty_after_maturity_running_rate"]); ?>" />
          </div>
          <div>
            <label>Penalty After Maturity Grace Period (Days)</label>
            <input type="text" name="penalty_after_maturity_grace_days" value="<?php echo htmlspecialchars((string) $formValues["penalty_after_maturity_grace_days"]); ?>" />
          </div>
          <div>
            <label>Penalty After Maturity Basis</label>
            <select name="penalty_after_maturity_basis" data-used-toggle="penalty_after_maturity_basis">
              <option value="Not Used" <?php echo $formValues["penalty_after_maturity_basis"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["penalty_after_maturity_basis"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="penalty_after_maturity_basis">Placeholder for maturity penalty basis.</div>
          </div>
          <div>
            <label>Disregard Payments After Maturity</label>
            <input type="checkbox" name="disregard_payments_after_maturity" <?php echo $formValues["disregard_payments_after_maturity"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Include Amort Penalty</label>
            <input type="checkbox" name="include_amort_penalty" <?php echo $formValues["include_amort_penalty"] ? "checked" : ""; ?> />
          </div>
        </div>

        <div class="form-grid" style="margin-top: 12px;">
          <div>
            <label>Past Due Interest (%)</label>
            <input type="text" name="past_due_interest_rate" value="<?php echo htmlspecialchars((string) $formValues["past_due_interest_rate"]); ?>" />
          </div>
          <div>
            <label>Past Due Interest Basis</label>
            <select name="past_due_interest_basis" data-used-toggle="past_due_interest_basis">
              <option value="Not Used" <?php echo $formValues["past_due_interest_basis"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["past_due_interest_basis"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="past_due_interest_basis">Placeholder for past due interest basis.</div>
          </div>
          <div>
            <label>Disregard Payments After Maturity</label>
            <input type="checkbox" name="past_due_disregard_payments" <?php echo $formValues["past_due_disregard_payments"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Penalty GL Account</label>
            <input type="text" name="penalty_gl_account" value="<?php echo htmlspecialchars((string) $formValues["penalty_gl_account"]); ?>" />
          </div>
          <div>
            <label>Grace Period Option</label>
            <select name="grace_period_option" data-used-toggle="grace_period_option">
              <option value="Not Used" <?php echo $formValues["grace_period_option"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["grace_period_option"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="grace_period_option">Placeholder for grace period options.</div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Approval Limits</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Secured Approval Min</label>
            <input type="text" name="secured_approval_min" value="<?php echo htmlspecialchars((string) $formValues["secured_approval_min"]); ?>" />
          </div>
          <div>
            <label>Secured Approval Max</label>
            <input type="text" name="secured_approval_max" value="<?php echo htmlspecialchars((string) $formValues["secured_approval_max"]); ?>" />
          </div>
          <div>
            <label>Secured Approver Count</label>
            <input type="text" name="secured_approver_count" value="<?php echo htmlspecialchars((string) $formValues["secured_approver_count"]); ?>" />
          </div>
          <div>
            <label>Unsecured Approval Min</label>
            <input type="text" name="unsecured_approval_min" value="<?php echo htmlspecialchars((string) $formValues["unsecured_approval_min"]); ?>" />
          </div>
          <div>
            <label>Unsecured Approval Max</label>
            <input type="text" name="unsecured_approval_max" value="<?php echo htmlspecialchars((string) $formValues["unsecured_approval_max"]); ?>" />
          </div>
          <div>
            <label>Unsecured Approver Count</label>
            <input type="text" name="unsecured_approver_count" value="<?php echo htmlspecialchars((string) $formValues["unsecured_approver_count"]); ?>" />
          </div>
        </div>
      </section>

      <section class="card" data-tab-panel="deductions" style="display: none;">
        <div class="section-title">
          <h3>Deductions</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Service Charge</label>
            <select name="service_charge_used" data-used-toggle="service_charge_used">
              <option value="Not Used" <?php echo $formValues["service_charge_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["service_charge_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
          </div>
          <div>
            <label>Savings Discounted</label>
            <select name="savings_discounted_used" data-used-toggle="savings_discounted_used">
              <option value="Not Used" <?php echo $formValues["savings_discounted_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["savings_discounted_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
          </div>
          <div>
            <label>GRT</label>
            <select name="grt_used" data-used-toggle="grt_used">
              <?php foreach ($grtOptions as $option) : ?>
                <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["grt_used"] === $option ? "selected" : ""; ?>>
                  <?php echo htmlspecialchars($option); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Insurance</label>
            <select name="insurance_used" data-used-toggle="insurance_used">
              <option value="Not Used" <?php echo $formValues["insurance_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["insurance_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
          </div>
          <div class="used-panel" data-used-panel="insurance_used" style="grid-column: 1 / -1;">
            <div class="form-grid">
              <div>
                <label>Insurance Name</label>
                <input type="text" name="insurance_name" value="<?php echo htmlspecialchars((string) $formValues["insurance_name"]); ?>" />
              </div>
              <div>
                <label>Flexible</label>
                <input type="checkbox" name="insurance_flexible" <?php echo $formValues["insurance_flexible"] ? "checked" : ""; ?> />
              </div>
              <div>
                <label>Insurance Provider Default</label>
                <select name="insurance_provider_default">
                  <?php foreach ($insuranceProviderOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["insurance_provider_default"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Insurance Table</label>
                <select name="insurance_table">
                  <?php foreach ($insuranceTableOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["insurance_table"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Enable Printing of Form</label>
                <select name="insurance_printing_form">
                  <?php foreach ($insurancePrintingOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["insurance_printing_form"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Insurance GL Account</label>
                <input type="text" name="insurance_gl_account" value="<?php echo htmlspecialchars((string) $formValues["insurance_gl_account"]); ?>" />
              </div>
              <div>
                <label>Insurance Product</label>
                <select name="insurance_product">
                  <?php foreach ($insuranceProductOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["insurance_product"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Other Deductions</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Notarial</label>
            <select name="notarial_used" data-used-toggle="notarial_used">
              <option value="Not Used" <?php echo $formValues["notarial_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["notarial_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="notarial_used">Placeholder for notarial deduction.</div>
          </div>
          <div>
            <label>Doc Stamp</label>
            <select name="doc_stamp_used" data-used-toggle="doc_stamp_used">
              <option value="Not Used" <?php echo $formValues["doc_stamp_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["doc_stamp_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="doc_stamp_used">Placeholder for doc stamp deduction.</div>
          </div>
          <div>
            <label>Inspection Fee</label>
            <select name="inspection_fee_used" data-used-toggle="inspection_fee_used">
              <option value="Not Used" <?php echo $formValues["inspection_fee_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["inspection_fee_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="inspection_fee_used">Placeholder for inspection fee.</div>
          </div>
          <div>
            <label>Filing Fee</label>
            <select name="filing_fee_used" data-used-toggle="filing_fee_used">
              <option value="Not Used" <?php echo $formValues["filing_fee_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["filing_fee_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="filing_fee_used">Placeholder for filing fee.</div>
          </div>
          <div>
            <label>Processing Fee</label>
            <select name="processing_fee_used" data-used-toggle="processing_fee_used">
              <option value="Not Used" <?php echo $formValues["processing_fee_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["processing_fee_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
          </div>
          <div class="used-panel" data-used-panel="processing_fee_used" style="grid-column: 1 / -1;">
            <div class="form-grid">
              <div>
                <label>Name</label>
                <input type="text" name="processing_fee_name" value="<?php echo htmlspecialchars((string) $formValues["processing_fee_name"]); ?>" />
              </div>
              <div>
                <label>Bracket Option</label>
                <select name="processing_fee_bracket_option">
                  <?php foreach ($processingFeeBracketOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["processing_fee_bracket_option"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Rate Options</label>
                <select name="processing_fee_rate_option">
                  <?php foreach ($processingFeeRateOptions as $option) : ?>
                    <option value="<?php echo htmlspecialchars($option); ?>" <?php echo $formValues["processing_fee_rate_option"] === $option ? "selected" : ""; ?>>
                      <?php echo htmlspecialchars($option); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label>Flexible</label>
                <input type="checkbox" name="processing_fee_flexible" <?php echo $formValues["processing_fee_flexible"] ? "checked" : ""; ?> />
              </div>
              <div>
                <label>Rates Table</label>
                <div class="form-note-text">Rates Table</div>
              </div>
              <div>
                <label>Processing Fee GL Account</label>
                <input type="text" name="processing_fee_gl_account" value="<?php echo htmlspecialchars((string) $formValues["processing_fee_gl_account"]); ?>" />
              </div>
            </div>
          </div>
          <div>
            <label>CTR Fund</label>
            <select name="ctr_fund_used" data-used-toggle="ctr_fund_used">
              <option value="Not Used" <?php echo $formValues["ctr_fund_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["ctr_fund_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="ctr_fund_used">Placeholder for CTR fund.</div>
          </div>
          <div>
            <label>Insurance 2</label>
            <select name="insurance2_used" data-used-toggle="insurance2_used">
              <option value="Not Used" <?php echo $formValues["insurance2_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["insurance2_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="insurance2_used">Placeholder for insurance 2.</div>
          </div>
          <div>
            <label>Deduction 8</label>
            <select name="deduction8_used" data-used-toggle="deduction8_used">
              <option value="Not Used" <?php echo $formValues["deduction8_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["deduction8_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="deduction8_used">Placeholder for deduction 8.</div>
          </div>
          <div>
            <label>Deduction 9</label>
            <select name="deduction9_used" data-used-toggle="deduction9_used">
              <option value="Not Used" <?php echo $formValues["deduction9_used"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["deduction9_used"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="deduction9_used">Placeholder for deduction 9.</div>
          </div>
        </div>
      </section>

      <section class="card" data-tab-panel="amortization" style="display: none;">
        <div class="section-title">
          <h3>Amortization</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>S. Charge Amortized</label>
            <select name="service_charge_amortized" data-used-toggle="service_charge_amortized">
              <option value="Not Used" <?php echo $formValues["service_charge_amortized"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["service_charge_amortized"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="service_charge_amortized">Placeholder for service charge amortization.</div>
          </div>
          <div>
            <label>Savings Amortized</label>
            <select name="savings_amortized" data-used-toggle="savings_amortized">
              <option value="Not Used" <?php echo $formValues["savings_amortized"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["savings_amortized"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="savings_amortized">Placeholder for savings amortization.</div>
          </div>
          <div>
            <label>Amort1</label>
            <select name="amort1" data-used-toggle="amort1">
              <option value="Not Used" <?php echo $formValues["amort1"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["amort1"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="amort1">Placeholder for amort1 settings.</div>
          </div>
          <div>
            <label>Amort2</label>
            <select name="amort2" data-used-toggle="amort2">
              <option value="Not Used" <?php echo $formValues["amort2"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["amort2"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="amort2">Placeholder for amort2 settings.</div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Amortization Options</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Amort'n Date Adjustment</label>
            <select name="amort_date_adjustment" data-used-toggle="amort_date_adjustment">
              <option value="Not Used" <?php echo $formValues["amort_date_adjustment"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["amort_date_adjustment"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="amort_date_adjustment">Placeholder for amortization date adjustment.</div>
          </div>
          <div>
            <label>Adjust on Holidays</label>
            <select name="amort_adjust_on_holidays" data-used-toggle="amort_adjust_on_holidays">
              <option value="Not Used" <?php echo $formValues["amort_adjust_on_holidays"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["amort_adjust_on_holidays"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="amort_adjust_on_holidays">Placeholder for holiday adjustment.</div>
          </div>
          <div>
            <label>Amortization Grace Period</label>
            <input type="text" name="amortization_grace_period" value="<?php echo htmlspecialchars((string) $formValues["amortization_grace_period"]); ?>" />
          </div>
          <div>
            <label>Auto-Debit Amortization</label>
            <input type="checkbox" name="auto_debit_amortization" <?php echo $formValues["auto_debit_amortization"] ? "checked" : ""; ?> />
          </div>
        </div>
      </section>

      <section class="card" data-tab-panel="others" style="display: none;">
        <div class="section-title">
          <h3>Others</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Savings Holdout</label>
            <input type="text" name="savings_holdout_value" value="<?php echo htmlspecialchars((string) $formValues["savings_holdout_value"]); ?>" />
          </div>
          <div>
            <label>Holdout Basis</label>
            <div class="radio-group">
              <label class="radio-option">
                <input type="radio" name="savings_holdout_basis" value="Percent" <?php echo $formValues["savings_holdout_basis"] === "Percent" ? "checked" : ""; ?> />
                %
              </label>
              <label class="radio-option">
                <input type="radio" name="savings_holdout_basis" value="Amount" <?php echo $formValues["savings_holdout_basis"] === "Amount" ? "checked" : ""; ?> />
                Amount
              </label>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Cure Period</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Daily</label>
            <input type="text" name="cure_period_daily" value="<?php echo htmlspecialchars((string) $formValues["cure_period_daily"]); ?>" />
          </div>
          <div>
            <label>Weekly</label>
            <input type="text" name="cure_period_weekly" value="<?php echo htmlspecialchars((string) $formValues["cure_period_weekly"]); ?>" />
          </div>
          <div>
            <label>Semi-Monthly</label>
            <input type="text" name="cure_period_semi_monthly" value="<?php echo htmlspecialchars((string) $formValues["cure_period_semi_monthly"]); ?>" />
          </div>
          <div>
            <label>Monthly</label>
            <input type="text" name="cure_period_monthly" value="<?php echo htmlspecialchars((string) $formValues["cure_period_monthly"]); ?>" />
          </div>
          <div>
            <label>Quarterly</label>
            <input type="text" name="cure_period_quarterly" value="<?php echo htmlspecialchars((string) $formValues["cure_period_quarterly"]); ?>" />
          </div>
          <div>
            <label>Semi-Annual</label>
            <input type="text" name="cure_period_semi_annual" value="<?php echo htmlspecialchars((string) $formValues["cure_period_semi_annual"]); ?>" />
          </div>
          <div>
            <label>Annual</label>
            <input type="text" name="cure_period_annual" value="<?php echo htmlspecialchars((string) $formValues["cure_period_annual"]); ?>" />
          </div>
          <div>
            <label>Lumpsum</label>
            <input type="text" name="cure_period_lumpsum" value="<?php echo htmlspecialchars((string) $formValues["cure_period_lumpsum"]); ?>" />
          </div>
          <div style="grid-column: 1 / -1;">
            <label>Enable Individual Cure Period</label>
            <input type="checkbox" name="enable_individual_cure_period" <?php echo $formValues["enable_individual_cure_period"] ? "checked" : ""; ?> />
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Other Options</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Enable Release Tagging</label>
            <input type="checkbox" name="enable_release_tagging" <?php echo $formValues["enable_release_tagging"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Cash Disbursed by Teller</label>
            <input type="checkbox" name="cash_disbursed_by_teller" <?php echo $formValues["cash_disbursed_by_teller"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Security Dependent PNs</label>
            <input type="checkbox" name="security_dependent_pns" <?php echo $formValues["security_dependent_pns"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>ACL Exempted</label>
            <input type="checkbox" name="acl_exempted" <?php echo $formValues["acl_exempted"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>ACL Assessment</label>
            <input type="text" name="acl_assessment" value="<?php echo htmlspecialchars((string) $formValues["acl_assessment"]); ?>" />
          </div>
          <div>
            <label>Comakership Limit</label>
            <input type="text" name="comakership_limit" value="<?php echo htmlspecialchars((string) $formValues["comakership_limit"]); ?>" />
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>Collection Sheet</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Collection List Display</label>
            <select name="collection_list_display" data-used-toggle="collection_list_display">
              <option value="Not Used" <?php echo $formValues["collection_list_display"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["collection_list_display"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="collection_list_display">Placeholder for collection list display.</div>
          </div>
          <div>
            <label>Collection List Orientation</label>
            <select name="collection_list_orientation" data-used-toggle="collection_list_orientation">
              <option value="Not Used" <?php echo $formValues["collection_list_orientation"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["collection_list_orientation"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="collection_list_orientation">Placeholder for collection list orientation.</div>
          </div>
          <div>
            <label>Balance to Show</label>
            <select name="balance_to_show" data-used-toggle="balance_to_show">
              <option value="Not Used" <?php echo $formValues["balance_to_show"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["balance_to_show"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="balance_to_show">Placeholder for balance display.</div>
          </div>
          <div>
            <label>Reflect Date Granted</label>
            <input type="checkbox" name="reflect_date_granted" <?php echo $formValues["reflect_date_granted"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Reflect Loan Amount</label>
            <input type="checkbox" name="reflect_loan_amount" <?php echo $formValues["reflect_loan_amount"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Reflect Savings Balance</label>
            <input type="checkbox" name="reflect_savings_balance" <?php echo $formValues["reflect_savings_balance"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Reflect Due Date</label>
            <input type="checkbox" name="reflect_duedate" <?php echo $formValues["reflect_duedate"] ? "checked" : ""; ?> />
          </div>
          <div>
            <label>Signature on Collection List</label>
            <input type="checkbox" name="signature_on_collection_list" <?php echo $formValues["signature_on_collection_list"] ? "checked" : ""; ?> />
          </div>
        </div>

        <div class="divider"></div>

        <div class="section-title">
          <h3>SMS Options</h3>
        </div>
        <div class="form-grid">
          <div>
            <label>Language</label>
            <select name="sms_language" data-used-toggle="sms_language">
              <option value="Not Used" <?php echo $formValues["sms_language"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["sms_language"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="sms_language">Placeholder for SMS language.</div>
          </div>
          <div>
            <label>Free SMS</label>
            <select name="sms_free" data-used-toggle="sms_free">
              <option value="Not Used" <?php echo $formValues["sms_free"] === "Not Used" ? "selected" : ""; ?>>Not Used</option>
              <option value="Used" <?php echo $formValues["sms_free"] === "Used" ? "selected" : ""; ?>>Used</option>
            </select>
            <div class="placeholder-panel" data-used-panel="sms_free">Placeholder for free SMS option.</div>
          </div>
          <div>
            <label>Show Unpaid Amorts</label>
            <input type="checkbox" name="sms_show_unpaid_amorts" <?php echo $formValues["sms_show_unpaid_amorts"] ? "checked" : ""; ?> />
          </div>
        </div>
      </section>

      <?php if ($isReadOnly) : ?>
        </fieldset>
      <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
