<?php $pageTitle = $pageTitle ?? 'OFRS'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($pageTitle) ?></title>
  <meta name="description" content="Online Fire Reporting System — futuristic major project with live reporting, tracking, maps, and fire safety guidance.">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
  
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header">
  <div class="container header-inner">
    <a href="index.php" class="brand">
      <span class="brand-mark"></span>
      <span>
        <strong>OFRS</strong>
        <small>Online Fire Reporting System</small>
      </span>
    </a>

    <nav class="top-nav" id="mainNav" aria-label="Primary navigation">
      <a href="index.php">Home</a>
      <a href="reporting.php">Report Fire</a>
      <a href="search.php">Track Report</a>
      <a href="fire-safety.php">Fire Safety</a>
      <a href="admin/index.php">Admin</a>
      <a href="#" class="open-contact">Contact</a>
    </nav>

    <div class="header-actions">
      <a class="call-chip" href="tel:112">Emergency 112</a>
      <button class="icon-btn" id="themeToggle" aria-label="Switch theme">◐</button>
      <button class="icon-btn menu-btn" id="navBurger" aria-label="Toggle menu">☰</button>
    </div>
  </div>
</header>

<?php if (!empty($_SESSION['toast'])): ?>
  <div class="toast toast-<?= h($_SESSION['toast']['type']) ?>" id="toast"><?= h($_SESSION['toast']['msg']) ?></div>
<?php unset($_SESSION['toast']); endif; ?>

<main id="main">