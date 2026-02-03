<?php
  require "../bootstrap.php";

  $pageTitle = "Settings";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Loan Product Settings";
  $activePage = "settings-loan-products";

  $settingsRepo = new SettingsRepository();
  $productStats = $settingsRepo->getProductStats();
  $loanProducts = $settingsRepo->getLoanProducts();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Manage loan products, rates, and service charges.</h2>
    <p>Keep your lending portfolio competitive with structured updates.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $productStats["active"]; ?></strong>
        <span>Active products</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["pending_updates"]; ?></strong>
        <span>Pending updates</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["draft"]; ?></strong>
        <span>Draft</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $productStats["archived"]; ?></strong>
        <span>Archived</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Products</h3>
      <button class="btn">Add Product</button>
    </div>
    <?php if (empty($loanProducts)) : ?>
      <div class="empty-row">No loan products available.</div>
    <?php else : ?>
      <div class="product-grid">
        <?php foreach ($loanProducts as $product) : ?>
          <div class="product">
            <div class="badge"></div>
            <strong><?php echo htmlspecialchars((string) $product["name"]); ?></strong>
            <span>Interest Rate: <?php echo htmlspecialchars((string) $product["interest_rate"]); ?></span>
            <span>Service Charge: <?php echo htmlspecialchars((string) $product["service_charge"]); ?></span>
            <span class="status"><?php echo htmlspecialchars((string) $product["status"]); ?></span>
            <button class="cta">Edit</button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
