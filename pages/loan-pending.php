<?php
  require "../bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Pending Applications";
  $activePage = "loan-pending";

  $loanRepo = new LoanRepository();
  $pendingStats = $loanRepo->getPendingStats();
  $pendingApplications = $loanRepo->getPendingApplications();

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
        <strong><?php echo (int) $pendingStats["pending_review"]; ?></strong>
        <span>Pending review</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["needs_documents"]; ?></strong>
        <span>Needs documents</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["supervisor_review"]; ?></strong>
        <span>Supervisor review</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["overdue"]; ?></strong>
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
          <?php if (empty($pendingApplications)) : ?>
            <tr>
              <td colspan="5" class="empty-row">No pending applications found.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($pendingApplications as $application) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $application["application_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["requested_amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["submitted_date"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $application["priority_class"]); ?>">
                    <?php echo htmlspecialchars((string) $application["priority_label"]); ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
