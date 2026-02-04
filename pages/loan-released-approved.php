<?php
  require "../bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Released Approved Loans";
  $activePage = "loan-released-approved";

  $loanRepo = new LoanRepository();
  $releaseStats = $loanRepo->getReleaseStats();
  $approvedReleases = $loanRepo->getApprovedReleases();

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
        <strong><?php echo (int) $releaseStats["ready_for_release"]; ?></strong>
        <span>Ready for release</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["released_today"]; ?></strong>
        <span>Released today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["scheduled_releases"]; ?></strong>
        <span>Scheduled releases</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["on_hold"]; ?></strong>
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
          <?php if (empty($approvedReleases)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No approved releases yet.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($approvedReleases as $release) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $release["loan_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["term"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["approval_date"]); ?></td>
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
