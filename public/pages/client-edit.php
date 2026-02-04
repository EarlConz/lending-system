<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Client Management";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Edit Client";
  $activePage = "client-edit";

  $clientRepo = new ClientRepository();
  $editStats = $clientRepo->getEditStats();
  $queueClients = $clientRepo->getClientsNeedingUpdates();
  $clientId = isset($_GET["id"]) && ctype_digit($_GET["id"]) ? (int) $_GET["id"] : null;
  $client = $clientRepo->getClientById($clientId);

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

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Edit Client Record</h3>
        <div>
          <button class="btn ghost">Save Changes</button>
          <button class="btn">Submit Review</button>
        </div>
      </div>

      <div class="form-grid">
        <div>
          <label>Client Name</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["name"]); ?>" />
        </div>
        <div>
          <label>Borrower ID</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["borrower_id"]); ?>" />
        </div>
        <div>
          <label>Primary Phone</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["phone"]); ?>" />
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" value="<?php echo htmlspecialchars((string) $client["email"]); ?>" />
        </div>
        <div>
          <label>Risk Category</label>
          <select>
            <option>Undefined</option>
            <option>VIP</option>
            <option>DOSRI</option>
            <option>RPT</option>
            <option>PEP</option>
          </select>
        </div>
        <div>
          <label>Verification Status</label>
          <select>
            <option>Verified</option>
            <option>Needs Follow-up</option>
          </select>
        </div>
        <div>
          <label>Present Address</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["address"]); ?>" />
        </div>
        <div>
          <label>Emergency Contact</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["emergency_contact"]); ?>" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Last Review Date</label>
          <input type="date" value="<?php echo htmlspecialchars((string) $client["last_review_date"]); ?>" />
        </div>
        <div>
          <label>Assigned Officer</label>
          <input type="text" value="<?php echo htmlspecialchars((string) $client["assigned_officer"]); ?>" />
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
</main>
<?php require "../partials/footer.php"; ?>
