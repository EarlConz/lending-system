<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Transaction For The Day";
  $activePage = "report-transactions-day";
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
        <strong>92</strong>
        <span>Transactions</span>
      </div>
      <div class="stat">
        <strong>67</strong>
        <span>Payments</span>
      </div>
      <div class="stat">
        <strong>18</strong>
        <span>Releases</span>
      </div>
      <div class="stat">
        <strong>7</strong>
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
          <input type="date" value="2026-02-03" />
        </div>
        <div>
          <label>Branch</label>
          <input type="text" value="002 - Main Branch" />
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
            <tr>
              <td>Payments</td>
              <td>67</td>
              <td>180,500</td>
            </tr>
            <tr>
              <td>Releases</td>
              <td>18</td>
              <td>650,000</td>
            </tr>
            <tr>
              <td>Adjustments</td>
              <td>7</td>
              <td>12,000</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
