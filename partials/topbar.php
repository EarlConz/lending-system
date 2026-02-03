<?php
  if (!isset($pageTitle)) {
    $pageTitle = "Lending System";
  }
  if (!isset($pageSubtitle)) {
    $pageSubtitle = "Tuesday, February 3, 2026";
  }
  if (!isset($topActionLabel)) {
    $topActionLabel = "Overview";
  }
  if (!isset($userLabel)) {
    $userLabel = "Admin";
  }
  if (!isset($searchPlaceholder)) {
    $searchPlaceholder = "Search";
  }
?>
<div class="topbar">
  <div class="page-title">
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <span><?php echo htmlspecialchars($pageSubtitle); ?></span>
  </div>
  <div class="searchbar">
    <strong>Search</strong>
    <input type="text" placeholder="<?php echo htmlspecialchars($searchPlaceholder); ?>" />
  </div>
  <div class="top-actions">
    <span class="pill info"><?php echo htmlspecialchars($topActionLabel); ?></span>
    <span class="pill"><?php echo htmlspecialchars($userLabel); ?></span>
  </div>
</div>
