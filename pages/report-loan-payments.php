<?php
  require "../bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Payment";
  $activePage = "report-loan-payments";

  $reportRepo = new ReportRepository();
  $paymentStats = $reportRepo->getLoanPaymentStats();
  $loanPayments = $reportRepo->getLoanPayments();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Payment report with delinquency markers.</h2>
    <p>Track remittances, short payments, and balances.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paymentStats["payments"]); ?></strong>
        <span>Payments</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paymentStats["on_time_rate"]); ?></strong>
        <span>On-time</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paymentStats["delinquent"]); ?></strong>
        <span>Delinquent</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $paymentStats["defaults"]); ?></strong>
        <span>Defaults</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Payment Report</h3>
      <div>
        <button class="btn ghost">Export CSV</button>
        <button class="btn">Generate</button>
      </div>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Due Date</th>
            <th>Paid</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($loanPayments)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No payment records available.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($loanPayments as $payment) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $payment["loan_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["due_date"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["paid_amount"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $payment["status_class"]); ?>">
                    <?php echo htmlspecialchars((string) $payment["status_label"]); ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
