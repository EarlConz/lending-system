<?php
  $pageTitle = "Loan Payment";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Edit Amortizations";
  $activePage = "payment-edit-amort";
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
        <strong>19</strong>
        <span>Accounts reviewed</span>
      </div>
      <div class="stat">
        <strong>4</strong>
        <span>Pending edits</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>New schedules</span>
      </div>
      <div class="stat">
        <strong>1</strong>
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
          <tr>
            <td>2026-02-15</td>
            <td>2,000</td>
            <td>400</td>
            <td>0</td>
            <td>2,400</td>
            <td>Standard</td>
          </tr>
          <tr>
            <td>2026-03-15</td>
            <td>2,000</td>
            <td>380</td>
            <td>0</td>
            <td>2,380</td>
            <td>Adjusted rate</td>
          </tr>
          <tr>
            <td>2026-04-15</td>
            <td>2,000</td>
            <td>360</td>
            <td>50</td>
            <td>2,410</td>
            <td>Penalty applied</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
