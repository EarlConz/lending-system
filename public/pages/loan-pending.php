<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Loan Application Release";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Pending Applications";
  $activePage = "loan-pending";

  $loanRepo = new LoanRepository();
  $errors = [];
  $success = isset($_GET["approved"]);

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "approve_application") {
      $applicationId = isset($_POST["application_id"]) && ctype_digit($_POST["application_id"])
        ? (int) $_POST["application_id"]
        : null;

      if ($applicationId === null) {
        $errors[] = "Invalid application selected.";
      } else {
        $application = $loanRepo->getLoanApplicationById($applicationId);
        if ($application === null) {
          $errors[] = "Application not found.";
        } elseif (($application["status"] ?? "") !== "Pending") {
          $errors[] = "Application is no longer pending.";
        } elseif (empty($application["client_id"]) || empty($application["product_id"])) {
          $errors[] = "Application is missing client or product details.";
        } elseif (empty($application["requested_amount"]) || empty($application["terms_months"])) {
          $errors[] = "Application is missing loan amount or term.";
        }
      }

      if (empty($errors) && $application !== null) {
        $loanRepo->createLoan([
          "loan_id" => $loanRepo->generateLoanId(),
          "client_id" => (int) $application["client_id"],
          "product_id" => (int) $application["product_id"],
          "amount" => $application["requested_amount"],
          "balance" => $application["requested_amount"],
          "term_months" => (int) $application["terms_months"],
          "approval_date" => date("Y-m-d"),
          "status" => "Active",
        ]);

        $loanRepo->updateLoanApplication($applicationId, [
          "status" => "Approved",
        ]);

        header("Location: loan-pending.php?approved=1");
        exit;
      }
    }
  }

  $pendingStats = $loanRepo->getPendingStats();
  $pendingApplications = $loanRepo->getPendingApplications();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Review pending loan applications and assign actions.</h2>
    <p>Keep the queue flowing with clear priorities and due dates.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $pendingStats["pending_review"]; ?></strong>
        <span>Pending review</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["needs_documents"]; ?></strong>
        <span>Needs documents</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["supervisor_review"]; ?></strong>
        <span>Supervisor review</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $pendingStats["overdue"]; ?></strong>
        <span>Overdue</span>
      </div>
    </div>
  </section>

  <section class="card" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Pending Queue</h3>
      <button class="btn ghost">Assign</button>
    </div>

    <?php if ($success) : ?>
      <div class="form-error" style="background: #e9f9ef; border-color: #bde7cb; color: #1d5b3a;">
        Application approved successfully.
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

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Application</th>
            <th>Borrower</th>
            <th>Requested</th>
            <th>Submitted</th>
            <th>Priority</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pendingApplications)) : ?>
            <tr>
              <td colspan="6" class="empty-row">No pending applications found.</td>
            </tr>
          <?php else : ?>
            <?php foreach ($pendingApplications as $application) : ?>
              <tr>
                <td><?php echo htmlspecialchars((string) $application["application_id"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["borrower"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["requested_amount"]); ?></td>
                <td><?php echo htmlspecialchars((string) $application["submitted_date"]); ?></td>
                <td>
                  <span class="status-pill <?php echo htmlspecialchars((string) $application["priority_class"]); ?>">
                    <?php echo htmlspecialchars((string) $application["priority_label"]); ?>
                  </span>
                </td>
                <td>
                  <div class="tw-flex tw-gap-2">
                    <form method="post" style="margin: 0;">
                      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
                      <input type="hidden" name="action" value="approve_application" />
                      <input type="hidden" name="application_id" value="<?php echo (int) $application["id"]; ?>" />
                      <button class="btn small" type="submit">Approve</button>
                    </form>
                    <a class="btn small ghost" href="loan-application-view.php?id=<?php echo (int) $application["id"]; ?>">View</a>
                  </div>
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
