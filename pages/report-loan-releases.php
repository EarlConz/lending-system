<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Loan Releases";
  $activePage = "report-loan-releases";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Release performance by branch and product.</h2>
    <p>Measure disbursement velocity and release totals.</p>
    <div class="stats">
      <div class="stat">
        <strong>98</strong>
        <span>Releases</span>
      </div>
      <div class="stat">
        <strong>3.2M</strong>
        <span>Total value</span>
      </div>
      <div class="stat">
        <strong>12</strong>
        <span>Branches</span>
      </div>
      <div class="stat">
        <strong>4</strong>
        <span>Products</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Release Report</h3>
      <button class="btn ghost">Export</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Release ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Product</th>
            <th>Release Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>REL-3301</td>
            <td>Jenna Uy</td>
            <td>42,000</td>
            <td>Salary Loan</td>
            <td>2026-02-02</td>
          </tr>
          <tr>
            <td>REL-3304</td>
            <td>Chris Tan</td>
            <td>35,000</td>
            <td>Emergency Loan</td>
            <td>2026-02-01</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
