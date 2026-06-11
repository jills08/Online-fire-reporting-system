<?php
require_once 'includes/config.php';
$pageTitle = 'OFRS | Search Records';
$where = [];
$params = [];

// Get search inputs
$name = trim($_GET['full_name'] ?? '');
$mobile = trim($_GET['mobile_number'] ?? '');
$location = trim($_GET['location_text'] ?? '');

// FIX: Aligned column names with your DB (fullName, mobileNumber, location)
if ($name !== '') { 
    $where[] = 'fullName LIKE ?'; 
    $params[] = "%$name%"; 
}
if ($mobile !== '') { 
    $where[] = 'mobileNumber LIKE ?'; 
    $params[] = "%$mobile%"; 
}
if ($location !== '') { 
    $where[] = 'location LIKE ?'; 
    $params[] = "%$location%"; 
}

$reports = [];
$searchAttempted = false;

// Only execute if at least one field is filled or search was clicked
if (isset($_GET['full_name'])) {
    $searchAttempted = true;
    if ($pdo && count($where) > 0) {
        // FIX: Changed tbl_firereport to tblfirereport and created_at to postingDate
        $sql = 'SELECT * FROM tblfirereport WHERE ' . implode(' OR ', $where) . ' ORDER BY id DESC LIMIT 100';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $reports = $stmt->fetchAll();
    }
}
include 'includes/header.php';
?>

<section class="page-shell">
  <div class="container page-head">
    <div class="kicker">Intelligence Archive</div>
    <h1 style="font-family:'Orbitron';">Search Incident Records</h1>
    <p>Recover public reports from the encrypted logs using name, mobile, or location data.</p>
  </div>

  <div class="container search-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 40px;">
    
    <section class="search-card glass-card" style="background: rgba(16, 32, 58, 0.6); padding: 30px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); height: fit-content;">
      <form method="get">
        <div class="field" style="margin-bottom: 20px;">
          <label for="full_name" style="font-size: 11px; color: #64748b; text-transform: uppercase;">Reporter Name</label>
          <input class="control" id="full_name" name="full_name" value="<?= h($name) ?>" placeholder="e.g. Kushaal" style="width: 100%;">
        </div>
        <div class="field" style="margin-bottom: 20px;">
          <label for="mobile_number" style="font-size: 11px; color: #64748b; text-transform: uppercase;">Mobile Number</label>
          <input class="control" id="mobile_number" name="mobile_number" value="<?= h($mobile) ?>" placeholder="e.g. 9876543210" maxlength="10">
        </div>
        <div class="field" style="margin-bottom: 30px;">
          <label for="location_text" style="font-size: 11px; color: #64748b; text-transform: uppercase;">Location</label>
          <input class="control" id="location_text" name="location_text" value="<?= h($location) ?>" placeholder="e.g. Andheri West">
        </div>
        <div class="hero-actions" style="display: flex; gap: 10px;">
          <button class="btn" type="submit" style="flex: 1;">Execute Search</button>
          <a class="btn-ghost" href="search.php" style="padding: 10px;">Reset</a>
        </div>
      </form>
    </section>

    <section class="results-area">
      <div class="section-head" style="margin-bottom: 25px;">
        <h2 style="font-family:'Orbitron'; font-size: 18px; color: #00d2ff;">
            <i class="fas fa-database"></i> <?= count($reports) ?> Records Recovered
        </h2>
      </div>

      <div class="result-list" style="display: grid; gap: 20px;">
        <?php foreach ($reports as $row): ?>
          <article class="result-card glass-card" style="padding: 20px; border-left: 4px solid #ff6b3d; background: rgba(255,255,255,0.02); border-radius: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <strong style="color: #ff6b3d; font-family: 'Orbitron'; font-size: 12px;">INCIDENT #<?= $row['id'] ?></strong>
              <span class="pill badge-<?= h(formatStatusClass($row['status'])) ?>" style="font-size: 10px;"><?= h($row['status'] ?: 'Reported') ?></span>
            </div>
            
            <p style="margin: 5px 0; font-weight: 600;"><?= h($row['location']) ?></p>
            <p class="small" style="color: #94a3b8; font-size: 12px;">
                Reporter: <strong><?= h($row['fullName']) ?></strong> | 
                Contact: <?= h($row['mobileNumber']) ?>
            </p>
            
            <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                <span class="small" style="color: #64748b; font-size: 11px;"><?= h($row['postingDate']) ?></span>
                <a class="btn-secondary" href="details.php?id=<?= (int)$row['id'] ?>" style="font-size: 11px; padding: 5px 15px;">View Full Intel →</a>
            </div>
          </article>
        <?php endforeach; ?>

        <?php if ($searchAttempted && !$reports): ?>
          <div class="alert alert-error" style="background: rgba(255, 93, 115, 0.1); border: 1px solid #ff5d73; padding: 20px; border-radius: 15px; text-align: center;">
            <i class="fas fa-exclamation-triangle"></i> No matching records found in the current temporal cycle.
          </div>
        <?php endif; ?>
      </div>
    </section>

  </div>
</section>

<?php include 'includes/footer.php'; ?>