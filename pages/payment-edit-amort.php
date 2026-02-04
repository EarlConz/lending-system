<?php
  require "../bootstrap.php";

  $pageTitle = "Loan Payment";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Edit Amortizations";
  $activePage = "payment-edit-amort";

  $paymentRepo = new PaymentRepository();
  $editStats = $paymentRepo->getEditAmortizationStats();
  $loanId = isset($_GET["loan_id"]) && ctype_digit($_GET["loan_id"]) ? (int) $_GET["loan_id"] : null;
  $schedule = $paymentRepo->getAmortizationSchedule($loanId);

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Adjust amortization schedules with clear visibility.</h2>
    <p>Track balance changes, rate adjustments, and notes.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $editStats["accounts_reviewed"]; ?></strong>
        <span>Accounts reviewed</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["pending_edits"]; ?></strong>
        <span>Pending edits</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["new_schedules"]; ?></strong>
        <span>New schedules</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["escalated"]; ?></strong>
        <span>Escalated</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Amortization Schedule</h3>
      <button class="btn">Save Updates</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Due Date</th>
            <th>Principal</th>
            <th>Interest</th>
            <th>Penalty</th>
            <th>Total</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($schedule)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No amortization schedule available.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($schedule as $row) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $row["due_date"]); ?></td>
                <td><?php echo htmlspecialchars((string) $row["principal"]); ?></td>
                <td><?php echo htmlspecialchars((string) $row["interest"]); ?></td>
                <td><?php echo htmlspecialchars((string) $row["penalty"]); ?></td>
                <td><?php echo htmlspecialchars((string) $row["total"]); ?></td>
                <td><?php echo htmlspecialchars((string) $row["note"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
