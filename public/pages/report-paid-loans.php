<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "List Of Paid Loans";
  $activePage = "report-paid-loans";

  $reportRepo = new ReportRepository();
  $paidStats = $reportRepo->getPaidLoanStats();
  $paidLoans = $reportRepo->getPaidLoans();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Track fully paid loans for audit and renewal.</h2>
    <p>Confirm closure dates, totals, and borrower retention.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paidStats["paid_year"]); ?></strong>
        <span>Paid this year</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paidStats["renewed"]); ?></strong>
        <span>Renewed</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paidStats["disputed"]); ?></strong>
        <span>Disputed</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paidStats["write_offs"]); ?></strong>
        <span>Write-offs</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Paid Loans Listing</h3>
      <button class="btn ghost">Export</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Paid Date</th>
            <th>Renewal</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($paidLoans)) : ?>
            <tr>
              <td colspan="5" class="empty-row">No paid loans to display.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($paidLoans as $loan) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $loan["loan_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["paid_date"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["renewal"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
