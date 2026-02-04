<?php
  require "../bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Transaction For The Day";
  $activePage = "report-transactions-day";

  $reportRepo = new ReportRepository();
  $transactionStats = $reportRepo->getTransactionStats();
  $transactionSummary = $reportRepo->getTransactionSummary();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Daily transaction overview by branch.</h2>
    <p>Monitor totals, posted payments, and releases for today.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $transactionStats["transactions"]; ?></strong>
        <span>Transactions</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $transactionStats["payments"]; ?></strong>
        <span>Payments</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $transactionStats["releases"]; ?></strong>
        <span>Releases</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $transactionStats["adjustments"]; ?></strong>
        <span>Adjustments</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Filters</h3>
        <button class="btn ghost">Apply</button>
      </div>
      <div class="form-grid">
        <div>
          <label>Date</label>
          <input type="date" value="<?php echo date("Y-m-d"); ?>" />
        </div>
        <div>
          <label>Branch</label>
          <input type="text" />
        </div>
        <div>
          <label>Transaction Type</label>
          <select>
            <option>All</option>
            <option>Payments</option>
            <option>Releases</option>
          </select>
        </div>
        <div>
          <label>Status</label>
          <select>
            <option>All</option>
            <option>Posted</option>
            <option>Pending</option>
          </select>
        </div>
      </div>
    </section>

    <section class="card">
      <div class="section-title">
        <h3>Summary</h3>
        <button class="btn ghost">Export</button>
      </div>
      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>Type</th>
              <th>Count</th>
              <th>Total</th>
            </tr>
        </thead>
        <tbody>
          <?php if (empty($transactionSummary)) : ?>
            <tr>
              <td colspan="3" class="empty-row">No transactions available for the selected day.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($transactionSummary as $summary) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $summary["type"]); ?></td>
                <td><?php echo htmlspecialchars((string) $summary["count"]); ?></td>
                <td><?php echo htmlspecialchars((string) $summary["total"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
