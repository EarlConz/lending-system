<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Administrator";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "Administration";
  $activePage = "admin";

  $userRepo = new UserRepository();
  $backupRepo = new BackupRepository();

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $action = $_POST["action"] ?? "";

    if ($action === "update_roles") {
      $roles = $_POST["roles"] ?? [];
      foreach ($roles as $userId => $role) {
        if (ctype_digit((string) $userId)) {
          $userRepo->updateRole((int) $userId, (string) $role);
        }
      }
    }

    if ($action === "create_backup") {
      $label = trim($_POST["label"] ?? "");
      if ($label === "") {
        $label = "Manual backup " . date("Y-m-d H:i");
      }
      $createdBy = $_SESSION["user"]["user_id"] ?? null;
      $backupRepo->createBackup($label, $createdBy ? (int) $createdBy : null);
    }

    header("Location: admin.php");
    exit;
  }

  $users = $userRepo->getAllUsers();
  $backups = $backupRepo->getAllBackups();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Administrator Console</h2>
    <p>Manage user roles and track database backup activity.</p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo count($users); ?></strong>
        <span>Users</span>
      </div>
      <div class="stat">
        <strong><?php echo count($backups); ?></strong>
        <span>Backups</span>
      </div>
      <div class="stat">
        <strong>0</strong>
        <span>Pending actions</span>
      </div>
      <div class="stat">
        <strong>0</strong>
        <span>Alerts</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>User Rights</h3>
        <button class="btn" form="user-rights-form">Save Changes</button>
      </div>
      <form id="user-rights-form" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
        <input type="hidden" name="action" value="update_roles" />
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($users)) : ?>
                <tr>
                  <td colspan="3" class="empty-row">No users yet.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($users as $user) : ?>
                  <tr>
                    <td><?php echo htmlspecialchars((string) $user["username"]); ?></td>
                    <td>
                      <select name="roles[<?php echo (int) $user["id"]; ?>]">
                        <option <?php echo $user["role"] === "Administrator" ? "selected" : ""; ?>>Administrator</option>
                        <option <?php echo $user["role"] === "Staff" ? "selected" : ""; ?>>Staff</option>
                        <option <?php echo $user["role"] === "Viewer" ? "selected" : ""; ?>>Viewer</option>
                      </select>
                    </td>
                    <td><?php echo htmlspecialchars((string) $user["created_at"]); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </form>
    </section>

    <div class="grid" style="gap: 16px;">
      <section class="card">
        <div class="section-title">
          <h3>Database Backup</h3>
          <button class="btn" form="backup-form">Create Backup</button>
        </div>
        <form id="backup-form" method="post">
          <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
          <input type="hidden" name="action" value="create_backup" />
          <div class="form-grid">
            <div>
              <label>Label</label>
              <input type="text" name="label" placeholder="Manual backup label" />
            </div>
          </div>
        </form>
      </section>

      <section class="card soft">
        <div class="section-title">
          <h3>Backup List</h3>
        </div>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>Label</th>
                <th>Created By</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($backups)) : ?>
                <tr>
                  <td colspan="3" class="empty-row">No backups recorded.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($backups as $backup) : ?>
                  <tr>
                    <td><?php echo htmlspecialchars((string) $backup["label"]); ?></td>
                    <td><?php echo htmlspecialchars((string) ($backup["created_by"] ?: "System")); ?></td>
                    <td><?php echo htmlspecialchars((string) $backup["created_at"]); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
