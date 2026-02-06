<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "CACOBEM";
  $activePage = "loan-cacobem";

  $cacobemRepo = new CacobemRepository();
  $errors = [];
  $success = isset($_GET["saved"]);

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
  $cacobemValues = $defaultCacobemValues;

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

  $selectedId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "update_cacobem") {
      $postedId = isset($_POST["cacobem_id"]) && ctype_digit($_POST["cacobem_id"]) ? (int) $_POST["cacobem_id"] : null;
      if ($postedId === null) {
        $errors[] = "Invalid CACOBEM record.";
      } else {
        $selectedId = $postedId;
      }

      $postedValues = $_POST["cacobem"] ?? [];
      foreach ($defaultCacobemValues as $key => $value) {
        $cacobemValues[$key] = trim((string) ($postedValues[$key] ?? ""));
      }

      $allowedAuthSchedules = ["15th", "30th", "15/30th"];
      if (!in_array($cacobemValues["authorization_schedule"], $allowedAuthSchedules, true)) {
        $cacobemValues["authorization_schedule"] = "";
      }

      $allowedSecurity = ["Secured", "Unsecured"];
      if (!in_array($cacobemValues["action_security"], $allowedSecurity, true)) {
        $cacobemValues["action_security"] = "";
      }

      $allowedTermUnit = ["days", "years"];
      if (!in_array($cacobemValues["pn_term_unit"], $allowedTermUnit, true)) {
        $cacobemValues["pn_term_unit"] = "";
      }

      $borrowerName = trim($cacobemValues["borrower_name"]);
      if ($borrowerName === "") {
        $errors[] = "Borrower name is required.";
      }

      $applicationDate = $normalizeDate($cacobemValues["application_date"], "Application date", true);
      $amountApplied = $normalizeNumber($cacobemValues["amount_applied"], "Amount applied");

      if (empty($errors) && $selectedId !== null) {
        $dataJson = json_encode($cacobemValues, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $cacobemRepo->update($selectedId, [
          "client_id" => null,
          "borrower_name" => $borrowerName !== "" ? $borrowerName : null,
          "application_date" => $applicationDate,
          "amount_applied" => $amountApplied,
          "data_json" => $dataJson ?: "{}",
        ]);

        header("Location: loan-cacobem.php?id=" . $selectedId . "&saved=1");
        exit;
      }
    }
  }

  $selectedRecord = null;
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($errors) && $selectedId !== null) {
    $selectedRecord = ["id" => $selectedId];
  }
  if ($selectedId !== null && empty($errors)) {
    $selectedRecord = $cacobemRepo->findById($selectedId);
    if ($selectedRecord === null) {
      $errors[] = "CACOBEM record not found.";
    }
  }

  if ($selectedRecord !== null && empty($errors) && $_SERVER["REQUEST_METHOD"] !== "POST") {
    $decoded = json_decode((string) ($selectedRecord["data_json"] ?? ""), true);
    if (!is_array($decoded)) {
      $decoded = [];
    }
    $cacobemValues = array_merge($defaultCacobemValues, array_intersect_key($decoded, $defaultCacobemValues));
    if (!empty($selectedRecord["borrower_name"])) {
      $cacobemValues["borrower_name"] = (string) $selectedRecord["borrower_name"];
    }
    if (!empty($selectedRecord["application_date"])) {
      $cacobemValues["application_date"] = (string) $selectedRecord["application_date"];
    }
    if (!empty($selectedRecord["amount_applied"])) {
      $cacobemValues["amount_applied"] = (string) $selectedRecord["amount_applied"];
    }
  }

  $records = $cacobemRepo->listAll();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>CACOBEM loan application documents.</h2>
    <p>Review, update, and print CACOBEM application forms.</p>
  </section>

  <?php if (!empty($errors) && $selectedRecord === null) : ?>
    <div class="form-error" style="margin-top: 20px;">
      <strong>Please review the errors below:</strong>
      <ul>
        <?php foreach ($errors as $error) : ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($selectedRecord === null) : ?>
    <section class="card print-hidden" style="margin-top: 24px;">
      <div class="section-title">
        <h3>CACOBEM Applications</h3>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Application Date</th>
              <th>Borrower Name</th>
              <th>Amount Applied</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($records)) : ?>
              <tr>
                <td colspan="4" class="empty-row">No CACOBEM applications yet.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($records as $record) : ?>
                <tr onclick="window.location='loan-cacobem.php?id=<?php echo (int) $record["id"]; ?>'" style="cursor: pointer;">
                  <td><?php echo htmlspecialchars((string) ($record["application_date"] ?? "")); ?></td>
                  <td><?php echo htmlspecialchars((string) ($record["borrower_name"] ?? "")); ?></td>
                  <td><?php echo htmlspecialchars((string) ($record["amount_applied"] ?? "")); ?></td>
                  <td><?php echo htmlspecialchars((string) ($record["created_at"] ?? "")); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($selectedRecord !== null) : ?>
    <section class="card cacobem-card" style="margin-top: 24px;">
      <div class="section-title print-hidden">
        <h3>CACOBEM Document</h3>
        <div>
          <button class="btn ghost" type="button" data-print>Print</button>
          <button class="btn" type="submit" form="cacobem-update-form">Save Changes</button>
        </div>
      </div>

      <?php if ($success) : ?>
        <div class="form-error" style="background: #e9f9ef; border-color: #bde7cb; color: #1d5b3a;">
          CACOBEM application updated successfully.
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

      <form id="cacobem-update-form" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
        <input type="hidden" name="action" value="update_cacobem" />
        <input type="hidden" name="cacobem_id" value="<?php echo htmlspecialchars((string) ($selectedRecord["id"] ?? "")); ?>" />

        <div class="cacobem-doc">
          <?php
            $cacobemShowAuthorizationDuplicate = true;
            $cacobemShowDuplicates = true;
            require "../partials/cacobem-page1.php";
            require "../partials/cacobem-page2.php";
          ?>
        </div>
      </form>
    </section>
  <?php endif; ?>
</main>
<?php require "../partials/footer.php"; ?>
