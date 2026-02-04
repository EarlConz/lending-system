<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Reports";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Listing";
  $activePage = "report-listing";

  $reportRepo = new ReportRepository();
  $listingStats = $reportRepo->getListingStats();
  $savedListings = $reportRepo->getSavedListings();

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
        <strong><?php echo (int) $listingStats["saved_templates"]; ?></strong>
        <span>Saved templates</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $listingStats["exports_week"]; ?></strong>
        <span>Exports this week</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $listingStats["shared"]; ?></strong>
        <span>Shared</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $listingStats["drafts"]; ?></strong>
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
        <?php if (empty($savedListings)) : ?>
          <li class="empty-row">No saved listings yet.</li>
        <?php else : ?>
          <?php foreach ($savedListings as $listing) : ?>
            <li>
              <span><?php echo htmlspecialchars((string) $listing["name"]); ?></span>
              <span class="status-pill <?php echo htmlspecialchars((string) $listing["status_class"]); ?>">
                <?php echo htmlspecialchars((string) $listing["status_label"]); ?>
              </span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
