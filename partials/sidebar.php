<?php
  if (!isset($activePage)) {
    $activePage = "";
  }
?>
<aside class="sidebar">
  <div class="logo">
    <div class="logo-badge"></div>
    <div>
      <div class="logo-title">Lending Systeme</div>
      <div class="menu-title">Menu</div>
    </div>
  </div>

  <nav class="nav">
    <details open>
      <summary>
        Client Management
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "client-new" ? "active" : ""; ?>" href="client-new.php">New Client</a>
        <a class="<?php echo $activePage === "client-edit" ? "active" : ""; ?>" href="client-edit.php">Edit Client</a>
      </div>
    </details>

    <details>
      <summary>
        Loan Application Release
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "loan-application" ? "active" : ""; ?>" href="loan-application.php">Application</a>
        <a class="<?php echo $activePage === "loan-released-approved" ? "active" : ""; ?>" href="loan-released-approved.php">Released Approved Loans</a>
        <a class="<?php echo $activePage === "loan-pending" ? "active" : ""; ?>" href="loan-pending.php">Pending Loan Application</a>
        <a class="<?php echo $activePage === "loan-delete-releases" ? "active" : ""; ?>" href="loan-delete-releases.php">Delete Loan Releases</a>
      </div>
    </details>

    <details>
      <summary>
        Loan Payment
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "payment-post" ? "active" : ""; ?>" href="payment-post.php">Post Payment</a>
        <a class="<?php echo $activePage === "payment-delete" ? "active" : ""; ?>" href="payment-delete.php">Delete Posted Payment</a>
        <a class="<?php echo $activePage === "payment-edit-amort" ? "active" : ""; ?>" href="payment-edit-amort.php">Edit Amortizations</a>
      </div>
    </details>

    <details>
      <summary>
        Reports
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "report-transactions-day" ? "active" : ""; ?>" href="report-transactions-day.php">Transaction For The Day</a>
        <a class="<?php echo $activePage === "report-loan-payments" ? "active" : ""; ?>" href="report-loan-payments.php">Loan Payment</a>
        <a class="<?php echo $activePage === "report-loan-releases" ? "active" : ""; ?>" href="report-loan-releases.php">Loan Releases</a>
        <a class="<?php echo $activePage === "report-loan-listing" ? "active" : ""; ?>" href="report-loan-listing.php">Loan Listing</a>
        <a class="<?php echo $activePage === "report-paid-loans" ? "active" : ""; ?>" href="report-paid-loans.php">List Of Paid Loans</a>
        <a class="<?php echo $activePage === "report-listing" ? "active" : ""; ?>" href="report-listing.php">Listing</a>
      </div>
    </details>

    <details>
      <summary>
        Settings
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "settings-loan-products" ? "active" : ""; ?>" href="settings-loan-products.php">Loan Product Settings</a>
      </div>
    </details>
  </nav>

  <div class="sidebar-footer">
    Branch: 002 - Main Branch
  </div>
  <a class="logout" href="#">Logout</a>
</aside>
