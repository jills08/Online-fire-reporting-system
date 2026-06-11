<?php
require_once 'includes/config.php';
$pageTitle = 'OFRS | Report Fire';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid CSRF token.';
    }

    $full_name = trim($_POST['full_name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $location_text = trim($_POST['location_text'] ?? '');
    $fire_type = trim($_POST['fire_type'] ?? '');
    $severity = trim($_POST['severity'] ?? 'medium');
    $people_at_risk = trim($_POST['people_at_risk'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $landmark = trim($_POST['landmark'] ?? '');
    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');

    if ($full_name === '') $errors[] = 'Full name is required.';
    if (!preg_match('/^[0-9]{10}$/', $mobile)) $errors[] = 'Enter a valid 10-digit mobile number.';
    if ($location_text === '') $errors[] = 'Location is required.';

    if (!$errors && $pdo) {
        $final_message = "Type: $fire_type | Severity: $severity | Risk: $people_at_risk | Landmark: $landmark | Note: $description";

        $stmt = $pdo->prepare("INSERT INTO tblfirereport (fullName, mobileNumber, location, message, latitude, longitude, status) VALUES (?, ?, ?, ?, ?, ?, 'Reported')");
        $stmt->execute([$full_name, $mobile, $location_text, $final_message, $latitude ?: null, $longitude ?: null]);

        $reportId = $pdo->lastInsertId();

        if (!empty($_FILES['photos']['name'][0])) {
            uploadPhotos($_FILES['photos'], $reportId);
        }

        // SUCCESS HANDLING: No popup. Redirect directly to search page.
        setToast('success', 'Incident Broadcasted Successfully.');
        header("Location: search.php");
        exit();
    }
}
include 'includes/header.php';
?>

<style>
    /* Tactical Dark UI Styling */
    .control {
        background: rgba(13, 22, 37, 0.8) !important;
        border: 1px solid rgba(124, 161, 255, 0.1) !important;
        color: #eaf2ff !important;
    }
    
    select.control option {
        background: #0d1625;
        color: #fff;
    }

    .severity-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .sev-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #94a3b8;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        font-family: 'Orbitron';
        font-size: 11px;
        transition: 0.3s;
    }

    .sev-btn:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }

    /* Tactical Active States */
    .active-low { border-color: #59d39a !important; color: #59d39a !important; background: rgba(89, 211, 154, 0.1) !important; box-shadow: 0 0 15px rgba(89, 211, 154, 0.2); }
    .active-medium { border-color: #ffbc00 !important; color: #ffbc00 !important; background: rgba(255, 188, 0, 0.1) !important; box-shadow: 0 0 15px rgba(255, 188, 0, 0.2); }
    .active-high { border-color: #ff6b3d !important; color: #ff6b3d !important; background: rgba(255, 107, 61, 0.1) !important; box-shadow: 0 0 15px rgba(255, 107, 61, 0.2); }
    .active-critical { border-color: #ff4b2b !important; color: #ff4b2b !important; background: rgba(255, 75, 43, 0.1) !important; box-shadow: 0 0 15px rgba(255, 75, 43, 0.2); }
</style>

<section class="page-shell">
  <div class="container split">
    <section class="form-shell">
      <div class="form-head">
        <div class="kicker">Tactical Deployment System</div>
        <h2>Report a fire in under 60 seconds</h2>
      </div>

      <?php if ($errors): ?><div class="alert alert-error"><?= h(implode(' ', $errors)) ?></div><?php endif; ?>

      <form method="post" action="reporting.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= h(csrfToken()) ?>">
        
        <div class="field-grid">
          <div class="field"><label for="full_name">Full Name</label><input class="control" id="full_name" name="full_name" placeholder="Reporter Identity" required></div>
          <div class="field"><label for="mobile">Mobile Number</label><input class="control" id="mobile" name="mobile" maxlength="10" placeholder="Primary Comms" required></div>
        </div>

        <div class="field"><label for="location_text">Incident Address</label><input class="control" id="location_text" name="location_text" placeholder="Physical location of fire" required></div>
        
        <div class="field"><label for="landmark">Landmark (Optional)</label><input class="control" id="landmark" name="landmark" placeholder="Visual reference for fire units"></div>

        <div class="field-grid">
          <div class="field"><label for="fire_type">Fire Type</label>
            <select class="control" id="fire_type" name="fire_type">
              <option value="Electrical">Electrical</option>
              <option value="Gas/LPG">Gas / LPG</option>
              <option value="Structural">Structural</option>
              <option value="Vehicle">Vehicle</option>
              <option value="Industrial">Industrial</option>
              <option value="Forest/Open Area">Forest / Open Area</option>
            </select>
          </div>
          <div class="field"><label for="people_at_risk">Tactical Risk (Optional)</label><input class="control" id="people_at_risk" name="people_at_risk" placeholder="Trapped persons / smoke level"></div>
        </div>

        <div class="field">
          <label>Severity Level</label>
          <input type="hidden" name="severity" value="medium" id="severity_input">
          <div class="severity-grid">
            <button type="button" class="sev-btn" data-level="low">Low</button>
            <button type="button" class="sev-btn active-medium" data-level="medium">Medium</button>
            <button type="button" class="sev-btn" data-level="high">High</button>
            <button type="button" class="sev-btn" data-level="critical">Critical</button>
          </div>
        </div>

        <div class="field"><label for="description">Situational Report (Optional)</label><textarea class="control" id="description" name="description" placeholder="Floor level, fire intensity, etc."></textarea></div>

        <div class="field-grid">
          <div class="field"><label for="latitude">Lat</label><input class="control" id="latitude" name="latitude" readonly></div>
          <div class="field"><label for="longitude">Long</label><input class="control" id="longitude" name="longitude" readonly></div>
        </div>

        <div class="field">
            <label for="photoInput">Visual Intel (Photos & Videos)</label>
            <input class="control" type="file" id="photoInput" name="photos[]" accept="image/*,video/*" multiple>
        </div>

        <div class="gallery" id="photoPreview"></div>

        <div class="hero-actions" style="margin-top:25px;">
          <button class="btn-secondary" type="button" id="gpsBtn"><i class="fas fa-location-arrow"></i> Scan GPS</button>
          <button class="btn" type="submit">Broadcast Incident</button>
        </div>
      </form>
    </section>

    <aside class="map-card glass-card">
      <div class="map-stage" id="reportMap"></div>
    </aside>
  </div>
</section>

<script>
document.querySelectorAll('.sev-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.sev-btn').forEach(b => b.classList.remove('active-low', 'active-medium', 'active-high', 'active-critical'));
        const level = this.getAttribute('data-level');
        this.classList.add('active-' + level);
        document.getElementById('severity_input').value = level;
    });
});
</script>

<?php include 'includes/footer.php'; ?>