<?php
  require "../bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Listing";
  $activePage = "report-loan-listing";

  $reportRepo = new ReportRepository();
  $listingStats = $reportRepo->getLoanListingStats();
  $loanListing = $reportRepo->getLoanListing();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Complete loan portfolio listing.</h2>
    <p>Snapshot of all active, closed, and delinquent loans.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $listingStats["total_loans"]); ?></strong>
        <span>Total loans</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $listingStats["active"]); ?></strong>
        <span>Active</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $listingStats["closed"]); ?></strong>
        <span>Closed</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $listingStats["delinquent"]); ?></strong>
        <span>Delinquent</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Listing</h3>
      <button class="btn ghost">Export CSV</button>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Balance</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($loanListing)) : ?>
            <tr>
              <td colspan="5" class="empty-row">No loans to display.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($loanListing as $loan) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $loan["loan_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $loan["balance"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $loan["status_class"]); ?>">
                    <?php echo htmlspecialchars((string) $loan["status_label"]); ?>
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
