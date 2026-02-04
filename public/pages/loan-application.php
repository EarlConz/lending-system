<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Application";
  $activePage = "loan-application";

  $loanRepo = new LoanRepository();
  $clientRepo = new ClientRepository();
  $settingsRepo = new SettingsRepository();
  $errors = [];
  $formValues = [
    "borrower_id" => "",
    "monthly_income" => "",
    "employment_info" => "",
    "requested_amount" => "",
    "terms_months" => "",
    "collateral" => "",
    "guarantor" => "",
  ];

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

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "create_application") {
      $formValues = array_merge($formValues, [
        "borrower_id" => trim((string) ($_POST["borrower_id"] ?? "")),
        "monthly_income" => trim((string) ($_POST["monthly_income"] ?? "")),
        "employment_info" => trim((string) ($_POST["employment_info"] ?? "")),
        "requested_amount" => trim((string) ($_POST["requested_amount"] ?? "")),
        "terms_months" => trim((string) ($_POST["terms_months"] ?? "")),
        "collateral" => trim((string) ($_POST["collateral"] ?? "")),
        "guarantor" => trim((string) ($_POST["guarantor"] ?? "")),
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

      $requestedAmount = $normalizeNumber($formValues["requested_amount"], "Requested amount");
      if ($requestedAmount === null) {
        $errors[] = "Requested amount is required.";
      }

      $termsMonths = trim($formValues["terms_months"]);
      if ($termsMonths === "" || !ctype_digit($termsMonths)) {
        $errors[] = "Terms must be a valid number of months.";
      }

      $monthlyIncome = $normalizeNumber($formValues["monthly_income"], "Monthly income");

      if (empty($errors) && $client !== null) {
        $applicationId = $loanRepo->generateApplicationId();

        $loanRepo->createLoanApplication([
          "application_id" => $applicationId,
          "client_id" => (int) $client["id"],
          "requested_amount" => $requestedAmount,
          "monthly_income" => $monthlyIncome,
          "employment_info" => $formValues["employment_info"] !== "" ? $formValues["employment_info"] : null,
          "terms_months" => (int) $termsMonths,
          "collateral" => $formValues["collateral"] !== "" ? $formValues["collateral"] : null,
          "guarantor" => $formValues["guarantor"] !== "" ? $formValues["guarantor"] : null,
          "status" => "Pending",
          "priority" => "Normal",
          "submitted_date" => date("Y-m-d"),
        ]);

        header("Location: loan-application.php?created=1");
        exit;
      }
    }
  }

  $applicationStats = $loanRepo->getApplicationStats();
  $recommendedProducts = $settingsRepo->getRecommendedProducts();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Capture applications and pre-qualify borrowers fast.</h2>
    <p>Verify identity, evaluate income sources, and recommend products.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $applicationStats["applications_today"]; ?></strong>
        <span>Applications today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $applicationStats["waiting_approval"]; ?></strong>
        <span>Waiting approval</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $applicationStats["auto_approved"]; ?></strong>
        <span>Auto-approved</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $applicationStats["high_risk"]; ?></strong>
        <span>High risk</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Loan Application</h3>
        <div>
          <button class="btn ghost" type="button">Save Draft</button>
          <button class="btn" type="submit" form="loan-application-form">Submit</button>
        </div>
      </div>
      <form id="loan-application-form" method="post">
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
      <div class="form-grid">
        <div>
          <label>Applicant Name</label>
          <input type="text" placeholder="Full name" />
        </div>
        <div>
          <label>Borrower ID</label>
          <input type="text" name="borrower_id" placeholder="BR-000278" value="<?php echo htmlspecialchars($formValues["borrower_id"]); ?>" />
        </div>
        <div>
          <label>Monthly Income</label>
          <input type="text" name="monthly_income" placeholder="35,000" value="<?php echo htmlspecialchars($formValues["monthly_income"]); ?>" />
        </div>
        <div>
          <label>Employment</label>
          <input type="text" name="employment_info" placeholder="Company, position" value="<?php echo htmlspecialchars($formValues["employment_info"]); ?>" />
        </div>
        <div>
          <label>Requested Amount</label>
          <input type="text" name="requested_amount" placeholder="50,000" value="<?php echo htmlspecialchars($formValues["requested_amount"]); ?>" />
        </div>
        <div>
          <label>Terms</label>
          <select name="terms_months">
            <option value="">Select</option>
            <option value="6" <?php echo $formValues["terms_months"] === "6" ? "selected" : ""; ?>>6 months</option>
            <option value="12" <?php echo $formValues["terms_months"] === "12" ? "selected" : ""; ?>>12 months</option>
            <option value="18" <?php echo $formValues["terms_months"] === "18" ? "selected" : ""; ?>>18 months</option>
          </select>
        </div>
      </div>
      <div class="divider"></div>
      <div class="form-grid">
        <div>
          <label>Collateral</label>
          <input type="text" name="collateral" placeholder="Optional" value="<?php echo htmlspecialchars($formValues["collateral"]); ?>" />
        </div>
        <div>
          <label>Guarantor</label>
          <input type="text" name="guarantor" placeholder="Optional" value="<?php echo htmlspecialchars($formValues["guarantor"]); ?>" />
        </div>
      </div>
      </form>
    </section>

    <section class="card soft">
      <div class="section-title">
        <h3>Recommended Products</h3>
        <button class="btn ghost">Compare</button>
      </div>
      <?php if (empty($recommendedProducts)) : ?>
        <div class="empty-row">No recommended products yet.</div>
      <?php else : ?>
        <div class="product-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
          <?php foreach ($recommendedProducts as $product) : ?>
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
  </div>
</main>
<?php require "../partials/footer.php"; ?>
