<?php
  require "../bootstrap.php";
  logout_user();
  header("Location: login.php");
  exit;
?>
