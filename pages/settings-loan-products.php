<?php
  $pageTitle = "Settings";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Loan Product Settings";
  $activePage = "settings-loan-products";
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
        <strong>4</strong>
        <span>Active products</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>Pending updates</span>
      </div>
      <div class="stat">
        <strong>1</strong>
        <span>Draft</span>
      </div>
      <div class="stat">
        <strong>0</strong>
        <span>Archived</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Loan Products</h3>
      <button class="btn">Add Product</button>
    </div>
    <div class="product-grid">
      <div class="product">
        <div class="badge"></div>
        <strong>Salary Loan</strong>
        <span>Interest Rate: 1.8%</span>
        <span>Service Charge: 0.5%</span>
        <span class="status">Active</span>
        <button class="cta">Edit</button>
      </div>
      <div class="product">
        <div class="badge"></div>
        <strong>Business Loan</strong>
        <span>Interest Rate: 2.1%</span>
        <span>Service Charge: 0.6%</span>
        <span class="status">Active</span>
        <button class="cta">Edit</button>
      </div>
      <div class="product">
        <div class="badge"></div>
        <strong>Emergency Loan</strong>
        <span>Interest Rate: 1.5%</span>
        <span>Service Charge: 0.4%</span>
        <span class="status">Active</span>
        <button class="cta">Edit</button>
      </div>
      <div class="product">
        <div class="badge"></div>
        <strong>Education Loan</strong>
        <span>Interest Rate: 1.2%</span>
        <span>Service Charge: 0.3%</span>
        <span class="status">Active</span>
        <button class="cta">Edit</button>
      </div>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
