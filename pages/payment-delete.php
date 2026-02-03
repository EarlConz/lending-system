<?php
  $pageTitle = "Loan Payment";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Delete Posted Payment";
  $activePage = "payment-delete";
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
        <strong>3</strong>
        <span>Reversals today</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>Pending approval</span>
      </div>
      <div class="stat">
        <strong>1</strong>
        <span>Completed</span>
      </div>
      <div class="stat">
        <strong>0</strong>
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
          <tr>
            <td>PAY-8832</td>
            <td>Maria Dela Cruz</td>
            <td>2,500</td>
            <td>2026-02-03</td>
            <td>Cash</td>
            <td><span class="status-pill warn">Pending</span></td>
          </tr>
          <tr>
            <td>PAY-8835</td>
            <td>Lea Domingo</td>
            <td>3,500</td>
            <td>2026-02-03</td>
            <td>Transfer</td>
            <td><span class="status-pill">Review</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
