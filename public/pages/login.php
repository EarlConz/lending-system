<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";

  $pageTitle = "Login";
  $pageSubtitle = date("l, F j, Y");
  $activePage = "";
  $loginError = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_csrf();
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    $userRepo = new UserRepository();
    $user = null;

    if ($username !== "") {
      $existing = $userRepo->findByUsername($username);
      $user = $existing ?: $userRepo->createUser($username, "Staff");

      if (!empty($user)) {
        $hasPassword = !empty($user["password_hash"]);
        if ($password !== "") {
          if ($hasPassword) {
            if (!password_verify($password, (string) $user["password_hash"])) {
              $loginError = "Invalid password.";
            }
          } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userRepo->updatePasswordHash((int) $user["id"], $hash);
            $user["password_hash"] = $hash;
          }
        }
      }
    }

    if ($loginError === "") {
      login_user([
        "user_id" => $user["id"] ?? null,
        "name" => $user["username"] ?? ($username !== "" ? $username : "Guest"),
        "role" => $user["role"] ?? "Staff",
      ]);

      header("Location: client-new.php");
      exit;
    }
  }
?>
<?php require "../partials/head.php"; ?>
<main class="main page auth-page">
  <section class="card auth-card">
    <div class="section-title">
      <h3>Login</h3>
    </div>
    <?php if ($loginError) : ?>
      <div class="auth-error"><?php echo htmlspecialchars($loginError); ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>" />
      <div class="form-grid">
        <div>
          <label>Username (Optional)</label>
          <input type="text" name="username" />
        </div>
        <div>
          <label>Password (Optional)</label>
          <input type="password" name="password" />
        </div>
      </div>
      <div class="auth-actions">
        <button class="btn" type="submit">Login</button>
      </div>
    </form>
  </section>
</main>
<?php require "../partials/footer.php"; ?>
