<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Client Management";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Edit Client";
  $activePage = "client-edit";

  $clientRepo = new ClientRepository();
  $branchRepo = new BranchRepository();
  $branches = $branchRepo->getAllBranches();
  $errors = [];
  $postAction = "";

  $clientId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";
    $postAction = $action;

    $removeId = isset($_POST["remove_beneficiary_id"]) && ctype_digit($_POST["remove_beneficiary_id"])
      ? (int) $_POST["remove_beneficiary_id"]
      : null;
    $postedClientId = isset($_POST["client_id"]) && ctype_digit($_POST["client_id"])
      ? (int) $_POST["client_id"]
      : null;

    if ($removeId !== null && $postedClientId !== null) {
      $clientRepo->deleteBeneficiary($removeId, $postedClientId);
      header("Location: client-edit.php?id=" . $postedClientId);
      exit;
    }

    if ($action === "update_client") {
      $clientId = isset($_POST["client_id"]) && ctype_digit($_POST["client_id"])
        ? (int) $_POST["client_id"]
        : null;

      if ($clientId === null) {
        $errors[] = "Invalid client selected.";
      } else {
        $firstName = trim((string) ($_POST["first_name"] ?? ""));
        $lastName = trim((string) ($_POST["last_name"] ?? ""));
        $middleName = trim((string) ($_POST["middle_name"] ?? ""));
        $borrowerId = trim((string) ($_POST["borrower_id"] ?? ""));
        $phonePrimary = trim((string) ($_POST["phone_primary"] ?? ""));
        $email = trim((string) ($_POST["email"] ?? ""));
        $riskCategory = trim((string) ($_POST["risk_category"] ?? ""));
        $verificationStatus = trim((string) ($_POST["verification_status"] ?? ""));
        $presentAddress = trim((string) ($_POST["present_address"] ?? ""));
        $emergencyContact = trim((string) ($_POST["emergency_contact"] ?? ""));
        $lastReviewDate = trim((string) ($_POST["last_review_date"] ?? ""));
        $assignedOfficer = trim((string) ($_POST["assigned_officer"] ?? ""));
        $branchIdValue = trim((string) ($_POST["branch_id"] ?? ""));

        if ($firstName === "") {
          $errors[] = "First name is required.";
        }
        if ($lastName === "") {
          $errors[] = "Last name is required.";
        }

        if ($branchIdValue === "" || !ctype_digit($branchIdValue)) {
          $errors[] = "Branch is required.";
        }

        if ($borrowerId !== "" && $clientRepo->borrowerIdExists($borrowerId, $clientId)) {
          $errors[] = "Borrower ID already exists.";
        }

        $allowedRisk = ["Undefined", "VIP", "DOSRI", "RPT", "PEP"];
        if ($riskCategory !== "" && !in_array($riskCategory, $allowedRisk, true)) {
          $errors[] = "Invalid risk category selection.";
        }

        $allowedVerification = ["Verified", "Needs Follow-up"];
        if ($verificationStatus !== "" && !in_array($verificationStatus, $allowedVerification, true)) {
          $errors[] = "Invalid verification status selection.";
        }

        if (empty($errors)) {
          $updateData = [
            "branch_id" => (int) $branchIdValue,
            "first_name" => $firstName,
            "middle_name" => $middleName !== "" ? $middleName : null,
            "last_name" => $lastName,
            "borrower_id" => $borrowerId !== "" ? $borrowerId : $clientRepo->generateBorrowerId(),
            "phone_primary" => $phonePrimary !== "" ? $phonePrimary : null,
            "email" => $email !== "" ? $email : null,
            "risk_category" => $riskCategory !== "" ? $riskCategory : "Undefined",
            "verification_status" => $verificationStatus !== "" ? $verificationStatus : "Needs Follow-up",
            "present_address" => $presentAddress !== "" ? $presentAddress : null,
            "emergency_contact" => $emergencyContact !== "" ? $emergencyContact : null,
            "last_review_date" => $lastReviewDate !== "" ? $lastReviewDate : null,
            "assigned_officer" => $assignedOfficer !== "" ? $assignedOfficer : null,
          ];

          $beneficiariesInput = $_POST["beneficiaries"] ?? [];
          $beneficiariesToCreate = [];

          if (is_array($beneficiariesInput)) {
            foreach ($beneficiariesInput as $index => $beneficiary) {
              if (!is_array($beneficiary)) {
                continue;
              }
              $relation = trim((string) ($beneficiary["relation"] ?? ""));
              $beneficiaryFirst = trim((string) ($beneficiary["first_name"] ?? ""));
              $beneficiaryLast = trim((string) ($beneficiary["last_name"] ?? ""));

              if ($relation === "" || $beneficiaryFirst === "" || $beneficiaryLast === "") {
                $errors[] = "Beneficiary " . ($index + 1) . " requires relation, first name, and last name.";
                continue;
              }

              $beneficiariesToCreate[] = [
                "relation" => $relation,
                "first_name" => $beneficiaryFirst,
                "middle_name" => trim((string) ($beneficiary["middle_name"] ?? "")),
                "last_name" => $beneficiaryLast,
                "birthdate" => trim((string) ($beneficiary["birthdate"] ?? "")),
                "gender" => trim((string) ($beneficiary["gender"] ?? "")),
              ];
            }
          }

          if (empty($errors)) {
            $clientRepo->withTransaction(function () use ($clientRepo, $clientId, $updateData, $beneficiariesToCreate) {
              $clientRepo->updateClient($clientId, $updateData);
              foreach ($beneficiariesToCreate as $beneficiary) {
                $clientRepo->createBeneficiary($clientId, [
                  "relation" => $beneficiary["relation"],
                  "first_name" => $beneficiary["first_name"],
                  "middle_name" => $beneficiary["middle_name"] !== "" ? $beneficiary["middle_name"] : null,
                  "last_name" => $beneficiary["last_name"],
                  "birthdate" => $beneficiary["birthdate"] !== "" ? $beneficiary["birthdate"] : null,
                  "gender" => $beneficiary["gender"] !== "" ? $beneficiary["gender"] : null,
                ]);
              }
            });

            header("Location: client-edit.php?id=" . $clientId);
            exit;
          }
        }
      }
    }

  }

  $editStats = $clientRepo->getEditStats();
  $queueClients = $clientRepo->getClientsNeedingUpdates();
  $client = $clientRepo->getClientById($clientId);
  $beneficiaries = $clientRepo->getBeneficiariesForClient($clientId);

  $formValues = [
    "branch_id" => (string) ($client["branch_id"] ?? ""),
    "first_name" => (string) ($client["first_name"] ?? ""),
    "middle_name" => (string) ($client["middle_name"] ?? ""),
    "last_name" => (string) ($client["last_name"] ?? ""),
    "borrower_id" => (string) ($client["borrower_id"] ?? ""),
    "phone_primary" => (string) ($client["phone"] ?? ""),
    "email" => (string) ($client["email"] ?? ""),
    "risk_category" => (string) ($client["risk_category"] ?? "Undefined"),
    "verification_status" => (string) ($client["verification_status"] ?? "Needs Follow-up"),
    "present_address" => (string) ($client["address"] ?? ""),
    "emergency_contact" => (string) ($client["emergency_contact"] ?? ""),
    "last_review_date" => (string) ($client["last_review_date"] ?? ""),
    "assigned_officer" => (string) ($client["assigned_officer"] ?? ""),
  ];

  if ($postAction === "update_client" && !empty($errors)) {
    $formValues = array_merge($formValues, [
      "branch_id" => trim((string) ($_POST["branch_id"] ?? "")),
      "first_name" => trim((string) ($_POST["first_name"] ?? "")),
      "middle_name" => trim((string) ($_POST["middle_name"] ?? "")),
      "last_name" => trim((string) ($_POST["last_name"] ?? "")),
      "borrower_id" => trim((string) ($_POST["borrower_id"] ?? "")),
      "phone_primary" => trim((string) ($_POST["phone_primary"] ?? "")),
      "email" => trim((string) ($_POST["email"] ?? "")),
      "risk_category" => trim((string) ($_POST["risk_category"] ?? "")),
      "verification_status" => trim((string) ($_POST["verification_status"] ?? "")),
      "present_address" => trim((string) ($_POST["present_address"] ?? "")),
      "emergency_contact" => trim((string) ($_POST["emergency_contact"] ?? "")),
      "last_review_date" => trim((string) ($_POST["last_review_date"] ?? "")),
      "assigned_officer" => trim((string) ($_POST["assigned_officer"] ?? "")),
    ]);
  }

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Update borrower profiles with controlled edits.</h2>
    <p>Track edits, verify documents, and keep contact details current.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $editStats["edits_today"]; ?></strong>
        <span>Edits today</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["pending_review"]; ?></strong>
        <span>Pending review</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["id_updates"]; ?></strong>
        <span>ID updates</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $editStats["risk_escalations"]; ?></strong>
        <span>Risk escalations</span>
      </div>
    </div>
  </section>

  <form id="client-edit-form" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
    <input type="hidden" name="action" value="update_client" />
    <input type="hidden" name="client_id" value="<?php echo htmlspecialchars((string) $clientId); ?>" />
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
  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Edit Client Record</h3>
        <div>
          <button class="btn ghost" type="submit" form="client-edit-form">Save Changes</button>
          <button class="btn" type="button">Submit Review</button>
        </div>
      </div>

      <div class="form-grid">
        <div>
          <label>Branch</label>
          <select name="branch_id">
            <option value="">Select branch</option>
            <?php foreach ($branches as $branch) : ?>
              <?php $branchId = (string) ($branch["id"] ?? ""); ?>
              <option value="<?php echo htmlspecialchars($branchId); ?>" <?php echo $branchId === (string) $formValues["branch_id"] ? "selected" : ""; ?>>
                <?php echo htmlspecialchars((string) ($branch["code"] ?? "")); ?> - <?php echo htmlspecialchars((string) ($branch["name"] ?? "")); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Borrower ID</label>
          <input type="text" name="borrower_id" value="<?php echo htmlspecialchars((string) $formValues["borrower_id"]); ?>" />
        </div>
        <div>
          <label>Last Name</label>
          <input type="text" name="last_name" value="<?php echo htmlspecialchars((string) $formValues["last_name"]); ?>" />
        </div>
        <div>
          <label>First Name</label>
          <input type="text" name="first_name" value="<?php echo htmlspecialchars((string) $formValues["first_name"]); ?>" />
        </div>
        <div>
          <label>Middle Name</label>
          <input type="text" name="middle_name" value="<?php echo htmlspecialchars((string) $formValues["middle_name"]); ?>" />
        </div>
        <div>
          <label>Primary Phone</label>
          <input type="text" name="phone_primary" value="<?php echo htmlspecialchars((string) $formValues["phone_primary"]); ?>" />
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars((string) $formValues["email"]); ?>" />
        </div>
        <div>
          <label>Risk Category</label>
          <select name="risk_category">
            <option value="Undefined" <?php echo $formValues["risk_category"] === "Undefined" ? "selected" : ""; ?>>Undefined</option>
            <option value="VIP" <?php echo $formValues["risk_category"] === "VIP" ? "selected" : ""; ?>>VIP</option>
            <option value="DOSRI" <?php echo $formValues["risk_category"] === "DOSRI" ? "selected" : ""; ?>>DOSRI</option>
            <option value="RPT" <?php echo $formValues["risk_category"] === "RPT" ? "selected" : ""; ?>>RPT</option>
            <option value="PEP" <?php echo $formValues["risk_category"] === "PEP" ? "selected" : ""; ?>>PEP</option>
          </select>
        </div>
        <div>
          <label>Verification Status</label>
          <select name="verification_status">
            <option value="Verified" <?php echo $formValues["verification_status"] === "Verified" ? "selected" : ""; ?>>Verified</option>
            <option value="Needs Follow-up" <?php echo $formValues["verification_status"] === "Needs Follow-up" ? "selected" : ""; ?>>Needs Follow-up</option>
          </select>
        </div>
        <div>
          <label>Present Address</label>
          <input type="text" name="present_address" value="<?php echo htmlspecialchars((string) $formValues["present_address"]); ?>" />
        </div>
        <div>
          <label>Emergency Contact</label>
          <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars((string) $formValues["emergency_contact"]); ?>" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Last Review Date</label>
          <input type="date" name="last_review_date" value="<?php echo htmlspecialchars((string) $formValues["last_review_date"]); ?>" />
        </div>
        <div>
          <label>Assigned Officer</label>
          <input type="text" name="assigned_officer" value="<?php echo htmlspecialchars((string) $formValues["assigned_officer"]); ?>" />
        </div>
      </div>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Clients Needing Updates</strong>
        <a href="#">View Queue</a>
      </header>
      <ul>
        <?php if (empty($queueClients)) : ?>
          <li class="empty-row">No clients in the update queue.</li>
        <?php else : ?>
          <?php foreach ($queueClients as $queueClient) : ?>
            <li>
              <span><?php echo htmlspecialchars((string) $queueClient["name"]); ?></span>
              <span class="status-pill <?php echo htmlspecialchars((string) $queueClient["status_class"]); ?>">
                <?php echo htmlspecialchars((string) $queueClient["status_label"]); ?>
              </span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </section>
  </div>

  <section class="card soft beneficiaries" style="margin-top: 24px;">
    <div class="section-title">
      <h3>Beneficiaries</h3>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Relation</th>
          <th>First Name</th>
          <th>Middle Name</th>
          <th>Last Name</th>
          <th>Birthdate</th>
          <th>Gender</th>
        </tr>
      </thead>
      <tbody>
        <tr class="beneficiary-entry" data-beneficiary-entry>
          <td>New</td>
          <td>
            <input type="text" placeholder="Relation" data-beneficiary-field="relation" />
          </td>
          <td>
            <input type="text" placeholder="First Name" data-beneficiary-field="first_name" />
          </td>
          <td>
            <input type="text" placeholder="Middle Name" data-beneficiary-field="middle_name" />
          </td>
          <td>
            <input type="text" placeholder="Last Name" data-beneficiary-field="last_name" />
          </td>
          <td>
            <input type="date" data-beneficiary-field="birthdate" />
          </td>
          <td>
            <div class="beneficiary-cell">
              <select data-beneficiary-field="gender">
                <option value="">Select</option>
                <option>Female</option>
                <option>Male</option>
                <option>Other</option>
              </select>
              <button class="btn small" type="button" data-action="add-beneficiary">Add</button>
            </div>
          </td>
        </tr>
        <?php if (empty($beneficiaries)) : ?>
          <tr data-empty-row>
            <td colspan="7" class="empty-row">No beneficiaries added yet.</td>
          </tr>
        <?php else : ?>
          <?php foreach ($beneficiaries as $beneficiary) : ?>
            <tr data-beneficiary-row>
              <td><?php echo htmlspecialchars((string) $beneficiary["index"]); ?></td>
              <td><?php echo htmlspecialchars((string) $beneficiary["relation"]); ?></td>
              <td><?php echo htmlspecialchars((string) $beneficiary["first_name"]); ?></td>
              <td><?php echo htmlspecialchars((string) $beneficiary["middle_name"]); ?></td>
              <td><?php echo htmlspecialchars((string) $beneficiary["last_name"]); ?></td>
              <td><?php echo htmlspecialchars((string) $beneficiary["birthdate"]); ?></td>
              <td>
                <div class="beneficiary-cell">
                  <span><?php echo htmlspecialchars((string) $beneficiary["gender"]); ?></span>
                  <button class="btn ghost small danger" type="submit" name="remove_beneficiary_id" value="<?php echo htmlspecialchars((string) $beneficiary["id"]); ?>">
                    Remove
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </section>
  </form>
</main>
<?php require "../partials/footer.php"; ?>
