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
              <th>Product</th>
              <td><?php echo htmlspecialchars((string) ($application["product_name"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Requested Amount</th>
              <td><?php echo htmlspecialchars((string) $application["requested_amount"]); ?></td>
            </tr>
            <tr>
              <th>Term</th>
              <td><?php echo htmlspecialchars((string) $application["terms_months"]); ?></td>
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
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
