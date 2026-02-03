<?php
  $pageTitle = "Loan Application Release";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Released Approved Loans";
  $activePage = "loan-released-approved";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Track approved loans ready for release.</h2>
    <p>Monitor approval dates, release status, and branch allocations.</p>
    <div class="stats">
      <div class="stat">
        <strong>12</strong>
        <span>Ready for release</span>
      </div>
      <div class="stat">
        <strong>6</strong>
        <span>Released today</span>
      </div>
      <div class="stat">
        <strong>3</strong>
        <span>Scheduled releases</span>
      </div>
      <div class="stat">
        <strong>1</strong>
        <span>On hold</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Approved Loan Releases</h3>
      <div>
        <button class="btn ghost">Export</button>
        <button class="btn">Release Selected</button>
      </div>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Term</th>
            <th>Approval Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>LN-24518</td>
            <td>Maria Dela Cruz</td>
            <td>50,000</td>
            <td>12 months</td>
            <td>2026-02-01</td>
            <td><span class="status-pill ok">Ready</span></td>
          </tr>
          <tr>
            <td>LN-24521</td>
            <td>James Torres</td>
            <td>30,000</td>
            <td>6 months</td>
            <td>2026-02-02</td>
            <td><span class="status-pill">Scheduled</span></td>
          </tr>
          <tr>
            <td>LN-24530</td>
            <td>Lea Domingo</td>
            <td>80,000</td>
            <td>18 months</td>
            <td>2026-02-02</td>
            <td><span class="status-pill warn">Hold</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
