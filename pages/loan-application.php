<?php
  $pageTitle = "Loan Application Release";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Application";
  $activePage = "loan-application";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Capture applications and pre-qualify borrowers fast.</h2>
    <p>Verify identity, evaluate income sources, and recommend products.</p>
    <div class="stats">
      <div class="stat">
        <strong>21</strong>
        <span>Applications today</span>
      </div>
      <div class="stat">
        <strong>7</strong>
        <span>Waiting approval</span>
      </div>
      <div class="stat">
        <strong>5</strong>
        <span>Auto-approved</span>
      </div>
      <div class="stat">
        <strong>2</strong>
        <span>High risk</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Loan Application</h3>
        <div>
          <button class="btn ghost">Save Draft</button>
          <button class="btn">Submit</button>
        </div>
      </div>
      <div class="form-grid">
        <div>
          <label>Applicant Name</label>
          <input type="text" placeholder="Full name" />
        </div>
        <div>
          <label>Borrower ID</label>
          <input type="text" placeholder="BR-000278" />
        </div>
        <div>
          <label>Monthly Income</label>
          <input type="text" placeholder="35,000" />
        </div>
        <div>
          <label>Employment</label>
          <input type="text" placeholder="Company, position" />
        </div>
        <div>
          <label>Requested Amount</label>
          <input type="text" placeholder="50,000" />
        </div>
        <div>
          <label>Terms</label>
          <select>
            <option>6 months</option>
            <option>12 months</option>
            <option>18 months</option>
          </select>
        </div>
      </div>
      <div class="divider"></div>
      <div class="form-grid">
        <div>
          <label>Collateral</label>
          <input type="text" placeholder="Optional" />
        </div>
        <div>
          <label>Guarantor</label>
          <input type="text" placeholder="Optional" />
        </div>
      </div>
    </section>

    <section class="card soft">
      <div class="section-title">
        <h3>Recommended Products</h3>
        <button class="btn ghost">Compare</button>
      </div>
      <div class="product-grid" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
        <div class="product">
          <div class="badge"></div>
          <strong>Salary Loan</strong>
          <span>Interest Rate: 1.8%</span>
          <span>Service Charge: 0.5%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
        <div class="product">
          <div class="badge"></div>
          <strong>Emergency Loan</strong>
          <span>Interest Rate: 1.5%</span>
          <span>Service Charge: 0.4%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
      </div>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
