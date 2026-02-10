<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Released Approved Loans";
  $activePage = "loan-released-approved";

  $loanRepo = new LoanRepository();
  $errors = [];
  $success = isset($_GET["released"]);
  $releaseStats = $loanRepo->getReleaseStats();
  $approvedLoans = $loanRepo->getApprovedUnreleasedLoans();
  $approvedApplications = $loanRepo->getApprovedApplications();
  $approvedCombined = array_merge($approvedLoans, $approvedApplications);
  usort($approvedCombined, static function (array $left, array $right): int {
    $leftDate = $left["approval_date"] ?? "";
    $rightDate = $right["approval_date"] ?? "";
    return strtotime((string) $rightDate) <=> strtotime((string) $leftDate);
  });
  $releasedApplications = $loanRepo->getReleasedApplications();

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "release_selected") {
      $postedIds = $_POST["release_ids"] ?? [];
      if (!is_array($postedIds)) {
        $postedIds = [];
      }
      $selectedIds = array_values(array_filter($postedIds, "ctype_digit"));
      $selectedIds = array_map("intval", $selectedIds);

      if (empty($selectedIds)) {
        $errors[] = "Select at least one loan to release.";
      }

      $approvedIds = array_map(static function ($release) {
        return (int) ($release["loan_pk"] ?? 0);
      }, $approvedLoans);
      $approvedLookup = array_flip($approvedIds);

      foreach ($selectedIds as $loanId) {
        if (!isset($approvedLookup[$loanId])) {
          $errors[] = "One or more selected loans are no longer available for release.";
          break;
        }
      }

      if (empty($errors)) {
        foreach ($selectedIds as $loanId) {
          $loan = null;
          foreach ($approvedLoans as $release) {
            if ((int) $release["loan_pk"] === $loanId) {
              $loan = $release;
              break;
            }
          }
          if ($loan === null) {
            continue;
          }
          $releaseId = $loanRepo->generateReleaseId();
          $loanRepo->createRelease([
            "release_id" => $releaseId,
            "loan_id" => $loanId,
            "amount" => $loan["amount"],
            "release_date" => date("Y-m-d"),
            "status" => "Released",
          ]);
        }

        header("Location: loan-released-approved.php?released=1");
        exit;
      }
    }
  }

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Track approved loans ready for release.</h2>
    <p>Monitor approval dates, release status, and branch allocations.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $releaseStats["ready_for_release"]; ?></strong>
        <span>Ready for release</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["released_today"]; ?></strong>
        <span>Released today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["scheduled_releases"]; ?></strong>
        <span>Scheduled releases</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $releaseStats["on_hold"]; ?></strong>
        <span>On hold</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Approved Loan Releases</h3>
      <div>
        <button class="btn ghost" type="button">Export</button>
        <button class="btn" type="submit" form="release-selected-form">Release Selected</button>
      </div>
    </div>

    <?php if ($success) : ?>
      <div class="form-error" style="background: #e9f9ef; border-color: #bde7cb; color: #1d5b3a;">
        Loan release completed successfully.
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)) : ?>
      <div class="form-error">
        <strong>Please review the errors below:</strong>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form id="release-selected-form" method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
      <input type="hidden" name="action" value="release_selected" />

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th></th>
              <th>Source</th>
              <th>Loan ID</th>
              <th>Borrower</th>
              <th>Amount</th>
              <th>Term</th>
              <th>Approval Date</th>
              <th>Status</th>
              <th>View</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($approvedCombined)) : ?>
              <tr>
                <td colspan="9" class="empty-row">No approved releases yet.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($approvedCombined as $release) : ?>
                <tr>
                  <td>
                    <?php if (!empty($release["is_selectable"])) : ?>
                      <input type="checkbox" name="release_ids[]" value="<?php echo (int) $release["loan_pk"]; ?>" />
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars((string) ($release["source"] ?? "")); ?></td>
                  <td><?php echo htmlspecialchars((string) ($release["loan_id"] ?? $release["application_id"] ?? "")); ?></td>
                  <td><?php echo htmlspecialchars((string) $release["borrower"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $release["term"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $release["approval_date"]); ?></td>
                  <td>
                    <span class="status-pill <?php echo htmlspecialchars((string) $release["status_class"]); ?>">
                      <?php echo htmlspecialchars((string) $release["status_label"]); ?>
                    </span>
                  </td>
                  <td>
                    <?php if (!empty($release["is_selectable"])) : ?>
                      <a class="btn small ghost" href="loan-view.php?id=<?php echo (int) $release["loan_pk"]; ?>">View</a>
                    <?php else : ?>
                      <span>-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </form>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Released Applications</h3>
    </div>
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Loan ID</th>
            <th>Borrower</th>
            <th>Amount</th>
            <th>Term</th>
            <th>Approval Date</th>
            <th>Status</th>
            <th>View</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($releasedApplications)) : ?>
            <tr>
              <td colspan="7" class="empty-row">No released applications yet.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($releasedApplications as $release) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $release["loan_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["term"]); ?></td>
                <td><?php echo htmlspecialchars((string) $release["approval_date"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $release["status_class"]); ?>">
                    <?php echo htmlspecialchars((string) $release["status_label"]); ?>
                  </span>
                </td>
                <td>
                  <a class="btn small ghost" href="loan-release-view.php?id=<?php echo (int) $release["release_pk"]; ?>">View</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
