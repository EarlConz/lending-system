<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Loan Payment";
  $activePage = "report-loan-payments";
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
        <strong>1,240</strong>
        <span>Payments</span>
      </div>
      <div class="stat">
        <strong>98%</strong>
        <span>On-time</span>
      </div>
      <div class="stat">
        <strong>14</strong>
        <span>Delinquent</span>
      </div>
      <div class="stat">
        <strong>3</strong>
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
          <tr>
            <td>LN-24518</td>
            <td>Maria Dela Cruz</td>
            <td>2,500</td>
            <td>2026-02-03</td>
            <td>2,500</td>
            <td><span class="status-pill ok">On-time</span></td>
          </tr>
          <tr>
            <td>LN-24530</td>
            <td>Lea Domingo</td>
            <td>3,500</td>
            <td>2026-02-01</td>
            <td>0</td>
            <td><span class="status-pill warn">Late</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
