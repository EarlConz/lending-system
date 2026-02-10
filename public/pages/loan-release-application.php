<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Release Application";
  $activePage = "loan-release-application";

  $loanRepo = new LoanRepository();
  $errors = [];
  $success = isset($_GET["released"]);
  $selectedIds = [];

  $releaseCandidates = $loanRepo->getReleaseCandidates();
  $candidateIds = array_map(static function ($candidate) {
    return (int) $candidate["loan_pk"];
  }, $releaseCandidates);
  $candidateLookup = array_flip($candidateIds);
  $candidateById = [];
  foreach ($releaseCandidates as $candidate) {
    $candidateById[(int) $candidate["loan_pk"]] = $candidate;
  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "release_applications") {
      $postedIds = $_POST["release_ids"] ?? [];
      if (!is_array($postedIds)) {
        $postedIds = [];
      }
      $selectedIds = array_values(array_filter($postedIds, "ctype_digit"));
      $selectedIds = array_map("intval", $selectedIds);

      if (empty($selectedIds)) {
        $errors[] = "Select at least one loan to release.";
      }

      foreach ($selectedIds as $loanId) {
        if (!isset($candidateLookup[$loanId])) {
          $errors[] = "One or more selected loans are no longer available for release.";
          break;
        }
      }

      if (empty($errors)) {
        foreach ($selectedIds as $loanId) {
          $candidate = $candidateById[$loanId];
          $releaseId = $loanRepo->generateReleaseId();
          $loanRepo->createRelease([
            "release_id" => $releaseId,
            "loan_id" => $loanId,
            "amount" => $candidate["initial_amount"],
            "release_date" => date("Y-m-d"),
            "status" => "Ready",
          ]);
        }

        header("Location: loan-release-application.php?released=1");
        exit;
      }
    }
  }

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>LOAN APPROVAL</h3>
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

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
      <input type="hidden" name="action" value="release_applications" />

      <div class="table-wrap">
        <table class="data-table release-table">
          <thead>
            <tr class="release-table-group">
              <th rowspan="2">#</th>
              <th rowspan="2">Client</th>
              <th rowspan="2">Product</th>
              <th rowspan="2">Current Loan Balance</th>
              <th rowspan="2">Date</th>
              <th colspan="2">Initial</th>
              <th rowspan="2">Interest Rate</th>
              <th colspan="2">Final</th>
              <th rowspan="2">Action</th>
              <th rowspan="2">Approval</th>
            </tr>
            <tr class="release-table-subhead">
              <th>Term</th>
              <th>Amount</th>
              <th>Term</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($releaseCandidates)) : ?>
              <tr>
                <td colspan="12" class="empty-row">No approved loans ready for release.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($releaseCandidates as $index => $candidate) : ?>
                <?php
                  $rate = $candidate["interest_rate"];
                  $rateLabel = ($rate !== null && $rate !== "") ? $rate . "%" : "";
                ?>
                <tr>
                  <td><?php echo (int) ($index + 1); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["client_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["product"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["current_balance"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["approval_date"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["initial_term"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["initial_amount"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $rateLabel); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["final_term"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $candidate["final_amount"]); ?></td>
                  <td>
                    <div class="release-actions">
                      <button class="btn ghost small" type="button">View</button>
                      <button class="btn ghost small" type="button">Edit</button>
                      <button class="btn ghost small" type="button">Delete</button>
                    </div>
                  </td>
                  <td>
                    <label class="release-approval">
                      <span>1/1</span>
                      <input
                        type="checkbox"
                        name="release_ids[]"
                        value="<?php echo (int) $candidate["loan_pk"]; ?>"
                        <?php echo in_array((int) $candidate["loan_pk"], $selectedIds, true) ? "checked" : ""; ?>
                      />
                    </label>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="release-footer">
        <button class="btn" type="submit">Submit</button>
      </div>
    </form>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
