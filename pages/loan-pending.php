<?php
  $pageTitle = "Loan Application Release";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Pending Applications";
  $activePage = "loan-pending";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Review pending loan applications and assign actions.</h2>
    <p>Keep the queue flowing with clear priorities and due dates.</p>
    <div class="stats">
      <div class="stat">
        <strong>14</strong>
        <span>Pending review</span>
      </div>
      <div class="stat">
        <strong>4</strong>
        <span>Needs documents</span>
      </div>
      <div class="stat">
        <strong>3</strong>
        <span>Supervisor review</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>Overdue</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Pending Queue</h3>
      <button class="btn ghost">Assign</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Application</th>
            <th>Borrower</th>
            <th>Requested</th>
            <th>Submitted</th>
            <th>Priority</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>AP-1182</td>
            <td>Angela Perez</td>
            <td>60,000</td>
            <td>2026-02-01</td>
            <td><span class="status-pill warn">High</span></td>
          </tr>
          <tr>
            <td>AP-1188</td>
            <td>Marco Reyes</td>
            <td>25,000</td>
            <td>2026-02-02</td>
            <td><span class="status-pill">Medium</span></td>
          </tr>
          <tr>
            <td>AP-1191</td>
            <td>Jessa Dizon</td>
            <td>40,000</td>
            <td>2026-02-03</td>
            <td><span class="status-pill ok">Normal</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
