<?php
  require "../bootstrap.php";

  $pageTitle = "Loan Payment";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Delete Posted Payment";
  $activePage = "payment-delete";

  $paymentRepo = new PaymentRepository();
  $deleteStats = $paymentRepo->getDeleteStats();
  $postedPayments = $paymentRepo->getPostedPayments();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Reverse posted payments with audit controls.</h2>
    <p>Ensure reversals are approved and documented.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $deleteStats["reversals_today"]; ?></strong>
        <span>Reversals today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["pending_approval"]; ?></strong>
        <span>Pending approval</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["completed"]; ?></strong>
        <span>Completed</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["rejected"]; ?></strong>
        <span>Rejected</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Posted Payments</h3>
      <button class="btn ghost">Request Delete</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Payment ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Method</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($postedPayments)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No posted payments found.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($postedPayments as $payment) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $payment["payment_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["date"]); ?></td>
                <td><?php echo htmlspecialchars((string) $payment["method"]); ?></td>
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
