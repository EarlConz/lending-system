<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "List Of Paid Loans";
  $activePage = "report-paid-loans";
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
        <strong>210</strong>
        <span>Paid this year</span>
      </div>
      <div class="stat">
        <strong>85</strong>
        <span>Renewed</span>
      </div>
      <div class="stat">
        <strong>12</strong>
        <span>Disputed</span>
      </div>
      <div class="stat">
        <strong>3</strong>
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
          <tr>
            <td>LN-24401</td>
            <td>Grace Lim</td>
            <td>28,000</td>
            <td>2026-01-28</td>
            <td>Eligible</td>
          </tr>
          <tr>
            <td>LN-24417</td>
            <td>Joel Santos</td>
            <td>65,000</td>
            <td>2026-01-30</td>
            <td>Not yet</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
