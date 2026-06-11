<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Incident Analysis</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --blue: #00d2ff; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        .main { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .glass-card { background: var(--glass); backdrop-filter: blur(15px); padding: 30px; border-radius: 20px; border: 1px solid var(--line); box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        
        .label { color: var(--blue); font-family: 'Orbitron'; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; display: block; opacity: 0.8; }
        .value { font-size: 15px; margin-bottom: 22px; display: block; color: white; line-height: 1.5; }
        
        .status-badge { padding: 6px 15px; border-radius: 50px; background: rgba(255,107,61,0.15); color: var(--orange); font-weight: bold; font-size: 11px; font-family: 'Orbitron'; border: 1px solid rgba(255,107,61,0.3); }
        
        /* Map Styling */
        #incidentMap { height: 350px; width: 100%; border-radius: 15px; border: 1px solid var(--line); background: #0d1625; margin-top: 15px; }
        .leaflet-tile { filter: brightness(0.6) invert(1) contrast(3) hue-rotate(200deg) saturate(0.3) brightness(0.7); }
        .leaflet-container { background: #050b14 !important; }

        .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 15px; margin-top: 15px; }
        .media-item { border-radius: 12px; border: 1px solid var(--line); overflow: hidden; background: #000; position: relative; }
        .media-item video, .media-item img { width: 100%; height: 160px; object-fit: cover; display: block; }
        
        .action-btn { 
            display: block; padding: 15px; background: var(--orange); color: white; 
            text-decoration: none; text-align: center; font-family: 'Orbitron'; 
            font-size: 12px; border-radius: 12px; transition: 0.3s; margin-top: 10px;
        }
        .action-btn:hover { background: #e65a2d; box-shadow: 0 5px 15px rgba(255, 107, 61, 0.3); }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <header style="margin-bottom: 30px;">
            <h1 style="font-family:'Orbitron'; margin:0; font-size: 24px;">Incident Intel Analysis</h1>
            <p style="color: #64748b; margin-top: 5px;">Comprehensive recovery of citizen-reported data and field evidence.</p>
        </header>

        <?php 
        $rid = intval($_GET['requestid']);
        $query = mysqli_query($con, "SELECT * FROM tblfirereport WHERE id='$rid'");
        while($row = mysqli_fetch_array($query)) {
        ?>
        <div class="detail-grid">
            <div class="glass-card">
                <span class="label">Reporter Details</span>
                <span class="value"><b><?php echo h($row['fullName']); ?></b></span>

                <span class="label">Tactical Contact</span>
                <span class="value"><?php echo h($row['mobileNumber']); ?></span>

                <span class="label">Deployment Location</span>
                <span class="value"><i class="fas fa-map-marker-alt" style="color:var(--orange);"></i> <?php echo h($row['location']); ?></span>

                <span class="label">Situation Report</span>
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px; border: 1px solid var(--line); font-size: 14px; color: #cbd5e1;">
                    <?php echo h($row['message']); ?>
                </div>
            </div>

            <div class="glass-card">
                <span class="label">Mission Status</span>
                <div style="margin-bottom: 25px;">
                    <span class="status-badge">
                        <i class="fas fa-satellite-dish" style="margin-right: 5px;"></i>
                        <?php echo ($row['status'] == "" || $row['status'] == "Reported") ? "NEW ALERT" : strtoupper($row['status']); ?>
                    </span>
                </div>

                <span class="label">Log Timestamp</span>
                <span class="value"><?php echo h($row['postingDate']); ?></span>

                <span class="label">Tactical Record ID</span>
                <span class="value" style="font-family: 'Orbitron'; color: var(--blue);">#OFRS-<?php echo $row['id']; ?></span>
                
                <hr style="border: 0; border-top: 1px solid var(--line); margin: 20px 0;">
                <a href="take-action.php?requestid=<?php echo $row['id']; ?>" class="action-btn">
                    <i class="fas fa-edit"></i> UPDATE MISSION STATUS
                </a>
            </div>
        </div>

        <div class="glass-card" style="margin-top: 20px;">
            <span class="label"><i class="fas fa-map-marked-alt"></i> Tactical Geospatial Intel</span>
            <?php if (!empty($row['latitude']) && !empty($row['longitude'])): ?>
                <div id="incidentMap"></div>
                <p style="font-size: 11px; color: #64748b; margin-top: 12px; font-family: monospace;">
                    GPS FIX: <?php echo h($row['latitude']); ?>, <?php echo h($row['longitude']); ?>
                </p>
            <?php else: ?>
                <div style="padding: 40px; text-align: center; color: #64748b; border: 1px dashed var(--line); border-radius: 15px;">
                    <i class="fas fa-search-location" style="font-size: 24px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                    No GPS coordinates available for this tactical record.
                </div>
            <?php endif; ?>
        </div>

        <div class="glass-card" style="margin-top: 20px;">
            <span class="label"><i class="fas fa-photo-video"></i> Multimedia Evidence Intel</span>
            <div class="media-grid">
                <?php 
                $photoQuery = mysqli_query($con, "SELECT photo_name FROM tbl_photos WHERE report_id = '$rid'");
                if (mysqli_num_rows($photoQuery) > 0) {
                    while($pRow = mysqli_fetch_array($photoQuery)) {
                        $file = $pRow['photo_name'];
                        $filePath = "../uploads/incidents/" . $file;
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $videoExts = ['mp4', 'webm', 'ogg'];

                        echo '<div class="media-item">';
                        if (in_array($ext, $videoExts)) {
                            echo '<video controls><source src="'.$filePath.'" type="video/'.$ext.'"></video>';
                        } else {
                            echo '<a href="'.$filePath.'" target="_blank"><img src="'.$filePath.'" alt="Evidence"></a>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo '<div style="grid-column: 1/-1; padding: 20px; text-align: center; color: #64748b; border: 1px dashed var(--line); border-radius: 12px;">';
                    echo 'No multimedia evidence detected.';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <?php } ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php 
        // Reset query to get coords if while loop finished
        mysqli_data_seek($query, 0); 
        $mapData = mysqli_fetch_array($query);
        if (!empty($mapData['latitude']) && !empty($mapData['longitude'])): 
        ?>
            var lat = <?php echo $mapData['latitude']; ?>;
            var lng = <?php echo $mapData['longitude']; ?>;
            var map = L.map('incidentMap').setView([lat, lng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var fireIcon = L.divIcon({
                className: 'custom-div-icon',
                html: "<div style='background-color: #ff6b3d; width: 18px; height: 18px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 15px #ff6b3d;'></div>",
                iconSize: [18, 18],
                iconAnchor: [9, 9]
            });

            L.marker([lat, lng], {icon: fireIcon}).addTo(map)
                .bindPopup("<b style='color:#050b14'>Incident Point</b><br><span style='color:#333'><?php echo h($mapData['location']); ?></span>")
                .openPopup();
        <?php endif; ?>
    });
    </script>
</body>
</html>
<?php } ?>