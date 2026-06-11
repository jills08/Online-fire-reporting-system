<?php
require_once 'includes/config.php';
$pageTitle = 'OFRS | Tactical Command Center';
$stats = getHomepageStats($pdo);
$trackResult = null;
$trackError = null;
$activeReports = fetchMapReports($pdo, 20);

// --- 1. PRIORITY DASHBOARD LOGIC ---
// Fetches the single most urgent active incident (In Progress > En Route > Assigned)
$stmtPriority = $pdo->prepare("
    SELECT * FROM tblfirereport 
    WHERE status IN ('Assigned', 'Team on the Way', 'Fire Relief in Progress') 
    ORDER BY 
        CASE 
            WHEN status = 'Fire Relief in Progress' THEN 1
            WHEN status = 'Team on the Way' THEN 2
            WHEN status = 'Assigned' THEN 3
            ELSE 4
        END ASC, 
        postingDate DESC 
    LIMIT 1
");
$stmtPriority->execute();
$priorityIncident = $stmtPriority->fetch();

// --- 2. LIVE NEWS LOGIC ---
$news_api_key = "dfb255df28c9c50d892e6692a828d4d8"; 
$news_url = "http://api.mediastack.com/v1/news?access_key=$news_api_key&keywords=fire%20emergency&countries=in&limit=3";
$cache_file = 'news_cache.json';
if (file_exists($cache_file) && (time() - filemtime($cache_file) < 3600)) {
    $news_data = json_decode(file_get_contents($cache_file), true);
} else {
    $response = @file_get_contents($news_url);
    $news_data = json_decode($response, true);
    file_put_contents($cache_file, $response);
}

// --- 3. TRACKING LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'track') {
    $ref = trim($_POST['refcode'] ?? '');
    if (!$ref) {
        $trackError = 'Please enter your mobile number.';
    } elseif ($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM tblfirereport WHERE mobileNumber = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([$ref]);
        $trackResult = $stmt->fetch();
        if (!$trackResult) $trackError = 'No report found for: ' . h($ref);
    }
}
include 'includes/header.php';
?>

<section class="hero">
  <div class="container hero-grid">
    <div class="hero-copy">
      <span class="eyebrow">Emergency civic-tech platform</span>
      <h1>Report fires. <span>Track response.</span> Save time when seconds matter.</h1>
      <p>The Online Fire Reporting System lets citizens file incidents in under 60 seconds and follow live progress through their mobile number.</p>
      <div class="hero-actions">
        <a href="reporting.php" class="btn">Report an Incident</a>
        <a href="#tracking" class="btn-secondary">Track My Report</a>
      </div>
    </div>

    <aside class="hero-panel glass-card">
      <div class="flex-between">
          <h3 style="font-family:'Orbitron'; font-size:12px; color:var(--orange);">Live Response Preview</h3>
          <div class="pulse-icon"></div>
      </div>
      
      <?php if ($priorityIncident): ?>
        <div class="status-list" style="margin-top:20px;">
            <div class="status-row">
                <span class="status-dot active"></span>
                <div>
                    <strong><?= h($priorityIncident['status']) ?></strong>
                    <div class="small"><?= h($priorityIncident['location']) ?></div>
                </div>
                <span class="pill badge-warning">Active</span>
            </div>
            <div style="background:rgba(255,255,255,0.03); padding:12px; border-radius:8px; margin-top:15px; border:1px solid rgba(255,255,255,0.05);">
                <p class="small" style="line-height:1.4; margin:0;"><?= h(substr($priorityIncident['message'], 0, 80)) ?>...</p>
            </div>
            <a href="search.php?id=<?= $priorityIncident['id'] ?>" class="btn-secondary" style="width:100%; margin-top:15px; text-align:center; font-size:10px;">Full Command View</a>
        </div>
      <?php else: ?>
        <div style="text-align:center; padding:40px 0;">
            <i class="fas fa-shield-alt" style="font-size:30px; opacity:0.2; margin-bottom:15px;"></i>
            <p class="small">Scanning sector... No active emergencies detected.</p>
        </div>
      <?php endif; ?>
    </aside>
  </div>
</section>

<section class="metrics">
  <div class="container metric-grid">
    <div class="metric"><strong><?= (int)$stats['total'] ?></strong><span>Total incidents</span></div>
    <div class="metric"><strong><?= (int)$stats['avg_response'] ?></strong><span>Avg. response (min)</span></div>
    <div class="metric"><strong><?= (int)$stats['resolution_rate'] ?>%</strong><span>Resolution rate</span></div>
    <div class="metric"><strong><?= (int)$stats['teams'] ?></strong><span>Active teams</span></div>
  </div>
</section>

<section class="section" id="tracking">
  <div class="container split-three">
    
    <div class="info-card glass-card">
      <div class="section-head">
        <div class="kicker">Public tracking</div>
        <h2>Live report status</h2>
      </div>
      <form method="post">
        <input type="hidden" name="action" value="track">
        <div class="field">
          <label for="trackRef">Mobile Number</label>
          <input class="control" id="trackRef" name="refcode" placeholder="Enter mobile number" maxlength="10">
        </div>
        <button class="btn" type="submit" style="width:100%">Track report</button>
      </form>

      <?php if ($trackResult): ?>
        <div class="detail-card active-track">
          <div class="flex-between">
            <span class="pill badge-info">ID #<?= $trackResult['id'] ?></span>
            <span class="pill badge-<?= h(formatStatusClass($trackResult['status'])) ?>"><?= h($trackResult['status'] ?: 'Reported') ?></span>
          </div>
          <p class="small"><b>Location:</b> <?= h($trackResult['location']) ?></p>
          <a class="read-more" href="search.php?id=<?= (int)$trackResult['id'] ?>">View Full Timeline →</a>
        </div>
      <?php endif; ?>
    </div>

    <div class="map-card glass-card">
      <div class="map-toolbar"><span class="pill badge-warning">Live incident map</span></div>
      <div class="map-stage" id="activeMap" style="height: 350px;"></div>
    </div>

    <aside class="news-sidebar glass-card">
        <div class="news-head"><h3>Satellite Intel</h3></div>
        <div class="news-feed">
            <?php if(!empty($news_data['data'])): ?>
                <?php foreach($news_data['data'] as $article): ?>
                    <div class="news-item">
                        <span class="news-source"><?= h($article['source']) ?></span>
                        <h4><?= h(substr($article['title'], 0, 60)) ?>...</h4>
                        <a href="<?= h($article['url']) ?>" target="_blank" class="news-link">Read Intelligence</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="small">Scanning alerts...</p>
            <?php endif; ?>
        </div>
    </aside>

  </div>
</section>

<style>
    .split-three { display: grid; grid-template-columns: 1fr 1.5fr 1fr; gap: 20px; }
    .glass-card { background: rgba(16, 32, 58, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; }
    .active-track { margin-top: 20px; padding: 15px; background: rgba(255,107,61,0.1); border-radius: 12px; border-left: 3px solid #ff6b3d; }
    .flex-between { display: flex; justify-content: space-between; align-items: center; }
    
    .pulse-icon { width: 8px; height: 8px; background: #ff4d4d; border-radius: 50%; box-shadow: 0 0 0 rgba(255, 77, 77, 0.4); animation: pulse 1.5s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(255, 77, 77, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 77, 77, 0); } }
    
    .news-item { margin-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px; }
    .news-source { font-size: 9px; color: #64748b; text-transform: uppercase; }
    .news-item h4 { font-size: 12px; margin: 5px 0; line-height: 1.3; color: #fff; }
    .news-link { font-size: 10px; color: #ff6b3d; text-decoration: none; font-family: 'Orbitron'; }
</style>

<script>
    window.activeReports = <?= json_encode($activeReports, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    // Auto-refresh the tactical dashboard every 60 seconds
    setTimeout(function(){ window.location.reload(); }, 60000);
</script>
<?php include 'includes/footer.php'; ?>