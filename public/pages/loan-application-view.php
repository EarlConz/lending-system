<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Application Details";
  $activePage = "loan-pending";

  $loanRepo = new LoanRepository();
  $applicationId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;
  $application = $applicationId !== null
    ? $loanRepo->getLoanApplicationViewById($applicationId)
    : null;

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Application Details</h3>
    </div>

    <?php if ($application === null) : ?>
      <div class="form-error">Application not found.</div>
    <?php else : ?>
      <?php
        $borrower = trim(($application["first_name"] ?? "") . " " . ($application["last_name"] ?? ""));
        $termLabel = trim((string) ($application["terms_months"] ?? ""));
        $termUnit = trim((string) ($application["term_unit"] ?? ""));
        $term = trim($termLabel . ($termUnit !== "" ? " " . $termUnit : ""));
        $termFixed = !empty($application["term_fixed"]) ? "Yes" : "No";
        $equalPrincipal = !empty($application["equal_principal"]) ? "Yes" : "No";
        $photoPath = trim((string) ($application["client_photo_path"] ?? ""));
      ?>
      <div class="table-wrap">
        <table class="data-table">
          <tbody>
            <tr>
              <th>Application ID</th>
              <td><?php echo htmlspecialchars((string) $application["application_id"]); ?></td>
            </tr>
            <tr>
              <th>Borrower</th>
              <td><?php echo htmlspecialchars($borrower); ?></td>
            </tr>
            <tr>
              <th>Borrower ID</th>
              <td><?php echo htmlspecialchars((string) ($application["borrower_id"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Contact Number</th>
              <td><?php echo htmlspecialchars((string) ($application["phone_primary"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Product</th>
              <td><?php echo htmlspecialchars((string) ($application["product_name"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Savings Account</th>
              <td><?php echo htmlspecialchars((string) ($application["savings_account"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Requested Amount</th>
              <td><?php echo htmlspecialchars((string) $application["requested_amount"]); ?></td>
            </tr>
            <tr>
              <th>Term</th>
              <td><?php echo htmlspecialchars($term); ?></td>
            </tr>
            <tr>
              <th>Term Fixed</th>
              <td><?php echo htmlspecialchars($termFixed); ?></td>
            </tr>
            <tr>
              <th>Interest Rate</th>
              <td><?php echo htmlspecialchars((string) ($application["interest_rate"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Interest Type</th>
              <td><?php echo htmlspecialchars((string) ($application["interest_type"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Equal Principal</th>
              <td><?php echo htmlspecialchars($equalPrincipal); ?></td>
            </tr>
            <tr>
              <th>Release Date</th>
              <td><?php echo htmlspecialchars((string) ($application["release_date"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Maturity Date</th>
              <td><?php echo htmlspecialchars((string) ($application["maturity_date"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Monthly Income</th>
              <td><?php echo htmlspecialchars((string) ($application["monthly_income"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Employment Info</th>
              <td><?php echo htmlspecialchars((string) ($application["employment_info"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Collateral</th>
              <td><?php echo htmlspecialchars((string) ($application["collateral"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Guarantor</th>
              <td><?php echo htmlspecialchars((string) ($application["guarantor"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Submitted Date</th>
              <td><?php echo htmlspecialchars((string) $application["submitted_date"]); ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?php echo htmlspecialchars((string) $application["status"]); ?></td>
            </tr>
            <tr>
              <th>Priority</th>
              <td><?php echo htmlspecialchars((string) $application["priority"]); ?></td>
            </tr>
            <tr>
              <th>Deduction Interest</th>
              <td><?php echo htmlspecialchars((string) ($application["deduction_interest"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Deduction Service Charge</th>
              <td><?php echo htmlspecialchars((string) ($application["deduction_service_charge"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Deduction Climbs</th>
              <td><?php echo htmlspecialchars((string) ($application["deduction_climbs"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Deduction Notarial Fee</th>
              <td><?php echo htmlspecialchars((string) ($application["deduction_notarial_fee"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Total Deductions</th>
              <td><?php echo htmlspecialchars((string) ($application["total_deductions"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Net Proceeds</th>
              <td><?php echo htmlspecialchars((string) ($application["net_proceeds"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Amortization Days</th>
              <td><?php echo htmlspecialchars((string) ($application["amortization_days"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Principal Interval</th>
              <td><?php echo htmlspecialchars((string) ($application["principal_interval"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Interval Adjustment</th>
              <td><?php echo htmlspecialchars((string) ($application["interval_adjustment"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Fixed Amortization</th>
              <td><?php echo htmlspecialchars((string) ($application["fixed_amortization"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Irregular Amortization</th>
              <td><?php echo htmlspecialchars((string) ($application["irregular_amortization"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Insurance Amount</th>
              <td><?php echo htmlspecialchars((string) ($application["insurance_amount"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Insurance Basis</th>
              <td><?php echo htmlspecialchars((string) ($application["insurance_basis"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Interest Amortized</th>
              <td><?php echo htmlspecialchars((string) ($application["interest_amortized"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Service Charge Amortized</th>
              <td><?php echo htmlspecialchars((string) ($application["service_charge_amortized"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Client Photo</th>
              <td>
                <?php if ($photoPath !== "") : ?>
                  <a class="btn small ghost" href="<?php echo htmlspecialchars($photoPath); ?>" target="_blank" rel="noopener">View Photo</a>
                <?php else : ?>
                  <span>-</span>
                <?php endif; ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
