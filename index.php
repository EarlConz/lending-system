<?php
  require __DIR__ . "/bootstrap.php";

  if (is_logged_in()) {
    header("Location: pages/client-new.php");
    exit;
  }

  header("Location: pages/login.php");
  exit;
?>
