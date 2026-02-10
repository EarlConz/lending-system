<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Details";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Details";
  $activePage = "loan-released-approved";

  $loanRepo = new LoanRepository();
  $loanId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;
  $loan = $loanId !== null ? $loanRepo->getLoanById($loanId) : null;

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Details</h3>
    </div>

    <?php if ($loan === null) : ?>
      <div class="form-error">Loan not found.</div>
    <?php else : ?>
      <?php
        $borrower = trim(($loan["first_name"] ?? "") . " " . ($loan["last_name"] ?? ""));
        $term = $loan["term_months"] ? $loan["term_months"] . " months" : "";
      ?>
      <div class="table-wrap">
        <table class="data-table">
          <tbody>
            <tr>
              <th>Loan ID</th>
              <td><?php echo htmlspecialchars((string) $loan["loan_id"]); ?></td>
            </tr>
            <tr>
              <th>Borrower</th>
              <td><?php echo htmlspecialchars($borrower); ?></td>
            </tr>
            <tr>
              <th>Borrower ID</th>
              <td><?php echo htmlspecialchars((string) ($loan["borrower_id"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Contact Number</th>
              <td><?php echo htmlspecialchars((string) ($loan["phone_primary"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Product</th>
              <td><?php echo htmlspecialchars((string) ($loan["product_name"] ?? "")); ?></td>
            </tr>
            <tr>
              <th>Amount</th>
              <td><?php echo htmlspecialchars((string) $loan["amount"]); ?></td>
            </tr>
            <tr>
              <th>Balance</th>
              <td><?php echo htmlspecialchars((string) $loan["balance"]); ?></td>
            </tr>
            <tr>
              <th>Term</th>
              <td><?php echo htmlspecialchars((string) $term); ?></td>
            </tr>
            <tr>
              <th>Approval Date</th>
              <td><?php echo htmlspecialchars((string) $loan["approval_date"]); ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?php echo htmlspecialchars((string) $loan["status"]); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
