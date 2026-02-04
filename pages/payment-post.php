<?php
  require "../bootstrap.php";

  $pageTitle = "Loan Payment";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Post Payment";
  $activePage = "payment-post";

  $paymentRepo = new PaymentRepository();
  $postStats = $paymentRepo->getPostStats();
  $recentPayments = $paymentRepo->getRecentPayments();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Post daily payments quickly and accurately.</h2>
    <p>Confirm borrower details, balance, and payment method.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $postStats["payments_today"]; ?></strong>
        <span>Payments today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $postStats["cash"]; ?></strong>
        <span>Cash</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $postStats["bank_transfer"]; ?></strong>
        <span>Bank transfer</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $postStats["auto_debit"]; ?></strong>
        <span>Auto-debit</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Payment Posting</h3>
        <button class="btn">Post Payment</button>
      </div>
      <div class="form-grid">
        <div>
          <label>Borrower</label>
          <input type="text" placeholder="Name or ID" />
        </div>
        <div>
          <label>Loan ID</label>
          <input type="text" placeholder="LN-24518" />
        </div>
        <div>
          <label>Amount</label>
          <input type="text" placeholder="2,500" />
        </div>
        <div>
          <label>Payment Method</label>
          <select>
            <option>Cash</option>
            <option>Bank Transfer</option>
            <option>Auto Debit</option>
          </select>
        </div>
        <div>
          <label>Reference</label>
          <input type="text" placeholder="OR-2026-202" />
        </div>
        <div>
          <label>Processed By</label>
          <input type="text" placeholder="T. Gomez" />
        </div>
      </div>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Payments</strong>
        <a href="#">View Ledger</a>
      </header>
      <ul>
        <?php if (empty($recentPayments)) : ?>
          <li class="empty-row">No recent payments.</li>
        <?php else : ?>
          <?php foreach ($recentPayments as $payment) : ?>
            <li>
              <span><?php echo htmlspecialchars((string) $payment["label"]); ?></span>
              <span class="status-pill <?php echo htmlspecialchars((string) $payment["status_class"]); ?>">
                <?php echo htmlspecialchars((string) $payment["status_label"]); ?>
              </span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
