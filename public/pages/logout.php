<?php
  require dirname(__DIR__, 2) . "/bootstrap.php";
  logout_user();
  header("Location: login.php");
  exit;
?>
