<?php
  $pageTitle = "Loan Payment";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Post Payment";
  $activePage = "payment-post";
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
        <strong>58</strong>
        <span>Payments today</span>
      </div>
      <div class="stat">
        <strong>12</strong>
        <span>Cash</span>
      </div>
      <div class="stat">
        <strong>31</strong>
        <span>Bank transfer</span>
      </div>
      <div class="stat">
        <strong>15</strong>
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
        <li>
          <span>LN-24518 · 2,500</span>
          <span class="status-pill ok">Posted</span>
        </li>
        <li>
          <span>LN-24521 · 1,950</span>
          <span class="status-pill">Pending</span>
        </li>
        <li>
          <span>LN-24530 · 3,500</span>
          <span class="status-pill">Pending</span>
        </li>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
