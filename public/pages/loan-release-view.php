<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Release Details";
  $activePage = "loan-released-approved";

  $loanRepo = new LoanRepository();
  $releaseId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;
  $release = $releaseId !== null ? $loanRepo->getReleaseById($releaseId) : null;

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Release Details</h3>
    </div>

    <?php if ($release === null) : ?>
      <div class="form-error">Release not found.</div>
    <?php else : ?>
      <?php
        $borrower = trim(($release["first_name"] ?? "") . " " . ($release["last_name"] ?? ""));
      ?>
      <div class="table-wrap">
        <table class="data-table">
          <tbody>
            <tr>
              <th>Release ID</th>
              <td><?php echo htmlspecialchars((string) $release["release_id"]); ?></td>
            </tr>
            <tr>
              <th>Loan ID</th>
              <td><?php echo htmlspecialchars((string) $release["loan_id"]); ?></td>
            </tr>
            <tr>
              <th>Borrower</th>
              <td><?php echo htmlspecialchars($borrower); ?></td>
            </tr>
            <tr>
              <th>Amount</th>
              <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
            </tr>
            <tr>
              <th>Release Date</th>
              <td><?php echo htmlspecialchars((string) $release["release_date"]); ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td><?php echo htmlspecialchars((string) $release["status"]); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
