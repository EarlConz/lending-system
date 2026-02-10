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
    <details <?php echo in_array($activePage, ["client-new", "client-edit"]) ? "open" : ""; ?>>
      <summary>
        Client Management
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "client-new" ? "active" : ""; ?>" href="../pages/client-new.php">New Client</a>
        <a class="<?php echo $activePage === "client-edit" ? "active" : ""; ?>" href="../pages/client-edit.php">Edit Client</a>
      </div>
    </details>

    <details <?php echo in_array($activePage, ["loan-application", "loan-released-approved", "loan-pending", "loan-delete-releases"]) ? "open" : ""; ?>>
      <summary>
        Loan Application Release
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "loan-application" ? "active" : ""; ?>" href="../pages/loan-application.php">Application</a>
        <a class="<?php echo $activePage === "loan-released-approved" ? "active" : ""; ?>" href="../pages/loan-released-approved.php">Released Approved Loans</a>
        <a class="<?php echo $activePage === "loan-pending" ? "active" : ""; ?>" href="../pages/loan-pending.php">Pending Loan Application</a>
        <a class="<?php echo $activePage === "loan-delete-releases" ? "active" : ""; ?>" href="../pages/loan-delete-releases.php">Delete Loan Releases</a>
        <a class="<?php echo $activePage === "loan-cacobem" ? "active" : ""; ?>" href="../pages/loan-cacobem.php">CACOBEM</a>
      </div>
    </details>

    <details <?php echo in_array($activePage, ["payment-post", "payment-delete", "payment-edit-amort"]) ? "open" : ""; ?>>
      <summary>
        Loan Payment
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "payment-post" ? "active" : ""; ?>" href="../pages/payment-post.php">Post Payment</a>
        <a class="<?php echo $activePage === "payment-delete" ? "active" : ""; ?>" href="../pages/payment-delete.php">Delete Posted Payment</a>
        <a class="<?php echo $activePage === "payment-edit-amort" ? "active" : ""; ?>" href="../pages/payment-edit-amort.php">Edit Amortizations</a>
      </div>
    </details>

    <details <?php echo in_array($activePage, ["report-transactions-day", "report-loan-payments", "report-loan-releases", "report-loan-listing", "report-paid-loans", "report-listing"]) ? "open" : ""; ?>>
      <summary>
        Reports
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "report-transactions-day" ? "active" : ""; ?>" href="../pages/report-transactions-day.php">Transaction For The Day</a>
        <a class="<?php echo $activePage === "report-loan-payments" ? "active" : ""; ?>" href="../pages/report-loan-payments.php">Loan Payment</a>
        <a class="<?php echo $activePage === "report-loan-releases" ? "active" : ""; ?>" href="../pages/report-loan-releases.php">Loan Releases</a>
        <a class="<?php echo $activePage === "report-loan-listing" ? "active" : ""; ?>" href="../pages/report-loan-listing.php">Loan Listing</a>
        <a class="<?php echo $activePage === "report-paid-loans" ? "active" : ""; ?>" href="../pages/report-paid-loans.php">List Of Paid Loans</a>
        <a class="<?php echo $activePage === "report-listing" ? "active" : ""; ?>" href="../pages/report-listing.php">Listing</a>
      </div>
    </details>

    <details <?php echo $activePage === "settings-loan-products" ? "open" : ""; ?>>
      <summary>
        Settings
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "settings-loan-products" ? "active" : ""; ?>" href="../pages/settings-loan-products.php">Loan Product Settings</a>
      </div>
    </details>

    <details <?php echo $activePage === "admin" ? "open" : ""; ?>>
      <summary>
        Administrator
        <span class="chev">v</span>
      </summary>
      <div class="subnav">
        <a class="<?php echo $activePage === "admin" ? "active" : ""; ?>" href="../pages/admin.php">Administrator</a>
      </div>
    </details>
  </nav>

  <div class="sidebar-footer">
    Branch: 002 - Main Branch
  </div>
  <a class="logout" href="../pages/logout.php">Logout</a>
</aside>
