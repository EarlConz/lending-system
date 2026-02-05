<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Delete Loan Releases";
  $activePage = "loan-delete-releases";

  $loanRepo = new LoanRepository();
  $deleteStats = $loanRepo->getReleaseDeletionStats();
  $releaseDeletions = $loanRepo->getReleaseDeletions();

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
        <strong><?php echo (int) $deleteStats["deletes_pending"]; ?></strong>
        <span>Deletes pending</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["supervisor_approvals"]; ?></strong>
        <span>Supervisor approvals</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["flagged_issues"]; ?></strong>
        <span>Flagged issues</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $deleteStats["blocked"]; ?></strong>
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
          <?php if (empty($releaseDeletions)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No release deletions queued.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($releaseDeletions as $release) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $release["release_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["released_date"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["reason"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $release["status_class"]); ?>">
                    <?php echo htmlspecialchars((string) $release["status_label"]); ?>
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
