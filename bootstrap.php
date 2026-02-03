<?php
declare(strict_types=1);

date_default_timezone_set("Asia/Manila");

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

spl_autoload_register(function (string $class): void {
  $paths = [
    __DIR__ . "/database/{$class}.php",
    __DIR__ . "/dal/{$class}.php",
  ];

  foreach ($paths as $path) {
    if (is_file($path)) {
      require_once $path;
      return;
    }
  }
});

function is_logged_in(): bool
{
  return isset($_SESSION["user"]) && is_array($_SESSION["user"]);
}

function require_login(): void
{
  if (PHP_SAPI === "cli") {
    return;
  }

  $publicPages = ["login.php", "logout.php"];
  $current = basename($_SERVER["SCRIPT_NAME"] ?? "");

  if (!in_array($current, $publicPages, true) && !is_logged_in()) {
    $dir = basename(dirname($_SERVER["SCRIPT_NAME"] ?? ""));
    $loginPath = $dir === "pages" ? "login.php" : "pages/login.php";
    header("Location: " . $loginPath);
    exit;
  }
}

function login_user(array $user): void
{
  $_SESSION["user"] = $user;
}

function logout_user(): void
{
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), "", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }
  session_destroy();
}

function csrf_token(): string
{
  if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
  }
  return $_SESSION["csrf_token"];
}

function require_csrf(): void
{
  $token = $_POST["csrf_token"] ?? "";
  if (!$token || !hash_equals($_SESSION["csrf_token"] ?? "", $token)) {
    http_response_code(403);
    echo "Invalid CSRF token.";
    exit;
  }
}

require_login();
