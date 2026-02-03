<?php
  $pageTitle = "Loan Application Release";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Delete Loan Releases";
  $activePage = "loan-delete-releases";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Audit released loans with precision and traceability.</h2>
    <p>Remove incorrect releases with full audit context.</p>
    <div class="stats">
      <div class="stat">
        <strong>5</strong>
        <span>Deletes pending</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>Supervisor approvals</span>
      </div>
      <div class="stat">
        <strong>1</strong>
        <span>Flagged issues</span>
      </div>
      <div class="stat">
        <strong>0</strong>
        <span>Blocked</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Release Deletions</h3>
      <button class="btn ghost">Request Approval</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Release ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Released</th>
            <th>Reason</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>REL-3301</td>
            <td>Jenna Uy</td>
            <td>42,000</td>
            <td>2026-01-29</td>
            <td>Duplicate</td>
            <td><span class="status-pill warn">Pending</span></td>
          </tr>
          <tr>
            <td>REL-3308</td>
            <td>Chris Tan</td>
            <td>35,000</td>
            <td>2026-01-30</td>
            <td>Wrong branch</td>
            <td><span class="status-pill">Review</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
