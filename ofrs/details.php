<?php
require_once 'includes/config.php';
$pageTitle = 'OFRS | Incident Analysis';
$id = (int)($_GET['id'] ?? 0);
$report = null;
$history = [];
$team = null;
$photos = [];

if ($pdo && $id > 0) {
    try {
        // 1. Fetch Main Report
        $stmt = $pdo->prepare('SELECT * FROM tblfirereport WHERE id = ?');
        $stmt->execute([$id]);
        $report = $stmt->fetch();

        if ($report) {
            // 2. Fetch History (ORDER BY id DESC ensures NEWEST history card is at the top)
            $h = $pdo->prepare('SELECT * FROM tbladminremarks WHERE reportId = ? ORDER BY id DESC');
            $h->execute([$id]);
            $history = $h->fetchAll();

            // 3. Fetch Team (Using assignTo from your DB screenshot)
            if (!empty($report['assignTo'])) {
                $t = $pdo->prepare('SELECT * FROM tblteams WHERE id = ?');
                $t->execute([(int)$report['assignTo']]);
                $team = $t->fetch();
            }

            // 4. Fetch Evidence
            $pStmt = $pdo->prepare("SELECT photo_name FROM tbl_photos WHERE report_id = ?");
            $pStmt->execute([$id]);
            $photos = $pStmt->fetchAll(PDO::FETCH_COLUMN);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

include 'includes/header.php';
?>

<style>
@keyframes pulse-orange {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 107, 61, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 107, 61, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 107, 61, 0); }
}
.pulse-active { animation: pulse-orange 2s infinite; }
</style>

<section class="page-shell">
  <div class="container page-head">
    <div class="kicker">Case Dossier</div>
    <h1 style="font-family:'Orbitron';">Incident Analysis</h1>
    <p>Comprehensive data recovery: reporter profile, tactical timeline, and multimedia evidence.</p>
  </div>

  <div class="container detail-grid" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; margin-top: 30px;">
    
    <section class="detail-card glass-card" style="padding: 30px; background: rgba(16, 32, 58, 0.6); border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
      <?php if (!$report): ?>
        <div class="alert alert-error">Data Corrupted: Report #<?= $id ?> not found in logs.</div>
      <?php else: ?>
        <div class="table-actions" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 25px; border-bottom: 1px solid var(--line); padding-bottom: 15px;">
          <strong style="font-family:'Orbitron'; color: var(--orange);">ID #<?= h($report['id']) ?></strong>
          <span class="pill badge-<?= h(formatStatusClass($report['status'])) ?>"><?= h($report['status'] ?: 'Reported') ?></span>
        </div>

        <div class="info-list" style="display: grid; gap: 15px;">
            <p><strong>Reporter:</strong> <?= h($report['fullName']) ?></p>
            <p><strong>Mobile:</strong> <?= h($report['mobileNumber']) ?></p>
            <p><strong>Location:</strong> <i class="fas fa-map-marker-alt" style="color:var(--orange);"></i> <?= h($report['location']) ?></p>
            
            <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 12px; border: 1px solid var(--line); margin: 10px 0;">
                <label style="font-size: 10px; text-transform: uppercase; color: #64748b; display: block; margin-bottom: 5px;">Tactical Message / Description</label>
                <span style="line-height: 1.6; color: #eaf2ff;"><?= h($report['message']) ?></span>
            </div>
            <p><strong>Logged:</strong> <?= h($report['postingDate']) ?></p>
        </div>
      <?php endif; ?>
    </section>

    <section class="detail-card glass-card" style="padding: 30px; background: rgba(16, 32, 58, 0.6); border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
      <h3 style="font-family:'Orbitron'; font-size: 16px; color: #fff; margin-bottom: 25px;">Live response preview</h3>
      
      <div class="live-timeline" style="display: flex; flex-direction: column; gap: 15px;">
        
        <?php 
        /**
         * 1. DISPLAY DYNAMIC HISTORY CARDS
         * This section loops through tbladminremarks. 
         * Every time the Admin submits the 'Take Action' form, a new card appears here.
         */
        if ($history): 
            foreach ($history as $index => $row): 
                $isActive = ($index === 0); 
                $dotColor = $isActive ? '#ff6b3d' : '#59d39a';
        ?>
                <div class="status-card" style="background: rgba(255,255,255,0.03); border: 1px solid <?= $isActive ? 'rgba(255,107,61,0.3)' : 'rgba(255,255,255,0.05)' ?>; padding: 20px; border-radius: 15px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div class="<?= $isActive ? 'pulse-active' : '' ?>" style="width: 12px; height: 12px; background: <?= $dotColor ?>; border-radius: 50%;"></div>
                        <div>
                            <strong style="display: block; font-size: 14px; color: #fff;"><?= h($row['status']) ?></strong>
                            <span style="font-size: 11px; color: #94a3b8;"><?= h($row['remark']) ?></span>
                        </div>
                    </div>
                    <span style="font-size: 11px; color: #64748b;"><?= date('h:i A', strtotime($row['remarkDate'])) ?></span>
                </div>
        <?php endforeach; 

        /**
         * 2. SMART SYNC FALLBACK
         * If the history table is empty BUT the main status has changed, we show one temporary "Active" card.
         * Once the admin adds a real remark, this fallback disappears and the real history cards take over.
         */
        elseif ($report['status'] && $report['status'] !== 'Reported'): ?>
            <div class="status-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,107,61,0.3); padding: 20px; border-radius: 15px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div class="pulse-active" style="width: 12px; height: 12px; background: #ff6b3d; border-radius: 50%;"></div>
                    <div>
                        <strong style="display: block; font-size: 14px; color: #fff;"><?= h($report['status']) ?></strong>
                        <span style="font-size: 11px; color: #94a3b8;">Tactical status updated by Dispatch.</span>
                    </div>
                </div>
                <span style="font-size: 11px; color: #64748b;"><?= !empty($report['assignTime']) ? date('h:i A', strtotime($report['assignTime'])) : 'Active' ?></span>
            </div>
        <?php endif; ?>

        <div class="status-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 20px; border-radius: 15px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 12px; height: 12px; background: #59d39a; border-radius: 50%; box-shadow: 0 0 10px #59d39a;"></div>
                <div>
                    <strong style="display: block; font-size: 14px; color: #fff;">Report submitted</strong>
                    <span style="font-size: 11px; color: #94a3b8;">Citizen request captured</span>
                </div>
            </div>
            <span style="font-size: 11px; color: #64748b;"><?= date('h:i A', strtotime($report['postingDate'])) ?></span>
        </div>
      </div>

      <hr style="border: 0; border-top: 1px solid var(--line); margin: 30px 0;">
      
      <h3 style="font-family:'Orbitron'; font-size: 14px; color: var(--orange); margin-bottom: 20px;">Assigned Fire Team</h3>
      <?php if ($team): ?>
        <div style="background: rgba(255, 107, 61, 0.05); padding: 20px; border-radius: 15px; border: 1px solid rgba(255, 107, 61, 0.2); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong style="display: block; font-size: 15px;"><?= h($team['teamName']) ?></strong>
                <span style="font-size: 11px; color: #94a3b8;">Commander: <?= h($team['teamLeaderName']) ?></span>
                <div style="margin-top: 8px; color: var(--orange); font-weight: bold; font-size: 13px;">
                    <i class="fas fa-phone-alt" style="font-size: 10px;"></i> <?= h($team['teamLeadMobno']) ?>
                </div>
            </div>
            <a href="tel:<?= h($team['teamLeadMobno']) ?>" style="background: var(--orange); color: #fff; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                <i class="fas fa-phone"></i>
            </a>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 20px; border: 1px dashed var(--line); border-radius: 15px;">
            <p class="small" style="color: #64748b; font-style: italic; margin: 0;">Tactical unit not yet deployed.</p>
        </div>
      <?php endif; ?>
    </section>
  </div>

  <?php if ($photos): ?>
  <div class="container glass-card" style="margin-top: 30px; padding: 30px; border-radius: 20px; background: rgba(16, 32, 58, 0.6); border: 1px solid rgba(0, 210, 255, 0.1);">
    <h3 style="font-family:'Orbitron'; font-size: 14px; color: #00d2ff; margin-bottom: 20px;"><i class="fas fa-photo-video"></i> Multimedia Field Evidence</h3>
    <div class="gallery" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
      <?php foreach ($photos as $file): 
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          $is_video = in_array($ext, ['mp4', 'webm', 'ogg']);
      ?>
        <div style="border-radius: 12px; overflow: hidden; border: 1px solid var(--line); background: #000; position: relative;">
            <?php if ($is_video): ?>
                <video controls style="width: 100%; height: 200px; display: block; object-fit: cover;">
                    <source src="uploads/incidents/<?= h($file) ?>" type="video/<?= $ext ?>">
                </video>
            <?php else: ?>
                <img src="uploads/incidents/<?= h($file) ?>" alt="Evidence" style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src)">
            <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>