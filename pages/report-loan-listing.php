<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Loan Listing";
  $activePage = "report-loan-listing";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Complete loan portfolio listing.</h2>
    <p>Snapshot of all active, closed, and delinquent loans.</p>
    <div class="stats">
      <div class="stat">
        <strong>2,350</strong>
        <span>Total loans</span>
      </div>
      <div class="stat">
        <strong>2,108</strong>
        <span>Active</span>
      </div>
      <div class="stat">
        <strong>190</strong>
        <span>Closed</span>
      </div>
      <div class="stat">
        <strong>52</strong>
        <span>Delinquent</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Listing</h3>
      <button class="btn ghost">Export CSV</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Balance</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>LN-24518</td>
            <td>Maria Dela Cruz</td>
            <td>50,000</td>
            <td>32,500</td>
            <td><span class="status-pill ok">Active</span></td>
          </tr>
          <tr>
            <td>LN-24530</td>
            <td>Lea Domingo</td>
            <td>80,000</td>
            <td>0</td>
            <td><span class="status-pill">Closed</span></td>
          </tr>
          <tr>
            <td>LN-24544</td>
            <td>Mark Tuazon</td>
            <td>45,000</td>
            <td>5,200</td>
            <td><span class="status-pill warn">Delinquent</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
