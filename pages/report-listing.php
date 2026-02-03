<?php
  $pageTitle = "Reports";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Listing";
  $activePage = "report-listing";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Custom report builder for quick audits.</h2>
    <p>Save filters, export formats, and report templates.</p>
    <div class="stats">
      <div class="stat">
        <strong>6</strong>
        <span>Saved templates</span>
      </div>
      <div class="stat">
        <strong>18</strong>
        <span>Exports this week</span>
      </div>
      <div class="stat">
        <strong>4</strong>
        <span>Shared</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>Drafts</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Build a Listing</h3>
        <button class="btn">Generate</button>
      </div>
      <div class="form-grid">
        <div>
          <label>Report Name</label>
          <input type="text" placeholder="Monthly Exposure" />
        </div>
        <div>
          <label>Branch</label>
          <input type="text" placeholder="All branches" />
        </div>
        <div>
          <label>Date Range</label>
          <select>
            <option>Last 30 days</option>
            <option>Last quarter</option>
            <option>Year to date</option>
          </select>
        </div>
        <div>
          <label>Export Type</label>
          <select>
            <option>PDF</option>
            <option>Excel</option>
            <option>CSV</option>
          </select>
        </div>
      </div>
    </section>

    <section class="list-panel">
      <header>
        <strong>Saved Listings</strong>
        <a href="#">Manage</a>
      </header>
      <ul>
        <li>
          <span>Monthly Exposure</span>
          <span class="status-pill">Shared</span>
        </li>
        <li>
          <span>Delinquency Snapshot</span>
          <span class="status-pill">Private</span>
        </li>
        <li>
          <span>Branch Performance</span>
          <span class="status-pill ok">Scheduled</span>
        </li>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
