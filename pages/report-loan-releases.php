<?php
  require "../bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Releases";
  $activePage = "report-loan-releases";

  $reportRepo = new ReportRepository();
  $releaseStats = $reportRepo->getLoanReleaseStats();
  $loanReleases = $reportRepo->getLoanReleases();

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
        <strong><?php echo htmlspecialchars((string) $releaseStats["releases"]); ?></strong>
        <span>Releases</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $releaseStats["total_value"]); ?></strong>
        <span>Total value</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $releaseStats["branches"]); ?></strong>
        <span>Branches</span>
      </div>
      <div class="stat">
        <strong><?php echo htmlspecialchars((string) $releaseStats["products"]); ?></strong>
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
          <?php if (empty($loanReleases)) : ?>
            <tr>
              <td colspan="5" class="empty-row">No loan releases available.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($loanReleases as $release) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $release["release_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["product"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["release_date"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
