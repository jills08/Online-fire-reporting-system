<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    $fdate = $_POST['fromdate'] ?? '';
    $tdate = $_POST['todate'] ?? '';

    // 1. DATA FOR LINE GRAPH (Frequency per day)
    $lineSql = "SELECT DATE(postingDate) as pdate, COUNT(id) as total FROM tblfirereport WHERE date(postingDate) BETWEEN '$fdate' AND '$tdate' GROUP BY DATE(postingDate) ORDER BY DATE(postingDate) ASC";
    $lineRes = mysqli_query($con, $lineSql);
    $dates = []; $counts = [];
    while($lr = mysqli_fetch_array($lineRes)) {
        $dates[] = date('M d', strtotime($lr['pdate']));
        $counts[] = $lr['total'];
    }

    // 2. DATA FOR RADAR CHART (Specific Fire Types)
    $electrical = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblfirereport WHERE message LIKE '%Type: Electrical%' AND date(postingDate) BETWEEN '$fdate' AND '$tdate'"));
    $forest = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblfirereport WHERE message LIKE '%Type: Forest%' AND date(postingDate) BETWEEN '$fdate' AND '$tdate'"));
    $gas = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblfirereport WHERE message LIKE '%Type: Gas/LPG%' AND date(postingDate) BETWEEN '$fdate' AND '$tdate'"));
    $vehicle = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblfirereport WHERE message LIKE '%Type: Vehicle%' AND date(postingDate) BETWEEN '$fdate' AND '$tdate'"));
    $structural = mysqli_num_rows(mysqli_query($con, "SELECT id FROM tblfirereport WHERE message LIKE '%Type: Structural%' AND date(postingDate) BETWEEN '$fdate' AND '$tdate'"));

    $radarValues = [$electrical, $forest, $gas, $vehicle, $structural];

    // 3. FETCH TABLE DATA
    $sql = "SELECT * FROM tblfirereport WHERE date(postingDate) BETWEEN '$fdate' AND '$tdate' ORDER BY id DESC";
    $query = mysqli_query($con, $sql);
    $totalFound = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Tactical Analytics Overlay</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --purple: #a855f7; --blue: #00d2ff; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        .main { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }
        
        .analysis-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; border-bottom: 1px solid var(--line); padding-bottom: 20px; }
        .range-pill { padding: 8px 20px; background: rgba(168, 85, 247, 0.1); border: 1px solid var(--purple); border-radius: 50px; font-family: 'Orbitron'; font-size: 11px; color: var(--purple); }
        
        .chart-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; margin-bottom: 30px; }
        
        /* SCALED UP CHART CARD */
        .chart-card { background: var(--glass); padding: 25px; border-radius: 20px; border: 1px solid var(--line); height: 550px; position: relative; }
        .chart-title { display: block; font-family: 'Orbitron'; font-size: 11px; color: #94a3b8; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        
        .unit-tag { position: absolute; top: 25px; right: 25px; font-size: 9px; font-family: 'Orbitron'; color: var(--orange); opacity: 0.7; border: 1px solid rgba(255,107,61,0.3); padding: 2px 8px; border-radius: 4px; }

        .glass-table { width: 100%; border-collapse: collapse; background: var(--glass); border-radius: 20px; overflow: hidden; border: 1px solid var(--line); }
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; font-size: 11px; color: var(--purple); letter-spacing: 1.5px; }
        .glass-table td { padding: 18px 20px; border-bottom: 1px solid var(--line); font-size: 14px; color: #cbd5e1; }
        .btn-inspect { padding: 8px 16px; background: rgba(168, 85, 247, 0.1); color: var(--purple); border: 1px solid var(--purple); border-radius: 8px; text-decoration: none; font-size: 10px; font-weight: bold; font-family: 'Orbitron'; transition: 0.3s; }
        .btn-inspect:hover { background: var(--purple); color: white; }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <div class="analysis-header">
            <div>
                <h1 style="font-family:'Orbitron'; margin:0; font-size: 26px;">Temporal Analysis Logs</h1>
                <p style="color: #64748b; margin-top: 5px;">Incident density and threat classification for the selected period.</p>
            </div>
            <div class="range-pill">
                <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($fdate); ?> <i class="fas fa-arrow-right" style="font-size: 10px; margin: 0 10px;"></i> <?php echo htmlspecialchars($tdate); ?>
            </div>
        </div>

        <div class="chart-grid">
            <div class="chart-card">
                <span class="unit-tag">Unit: Incident Count</span>
                <span class="chart-title"><i class="fas fa-chart-line"></i> Incident Occurrence Rate</span>
                <div style="height: 450px; margin-top: 10px;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <span class="chart-title"><i class="fas fa-bullseye"></i> Threat Severity Matrix</span>
                <div style="height: 450px; margin-top: 10px;">
                    <canvas id="radarChart"></canvas>
                </div>
            </div>
        </div>

        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $cnt = 1;
                if($totalFound > 0) {
                    while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo htmlspecialchars($row['fullName']); ?></b></td>
                    <td><?php echo htmlspecialchars($row['mobileNumber']); ?></td>
                    <td><i class="fas fa-map-pin" style="font-size:12px; color:var(--purple); margin-right:5px;"></i> <?php echo htmlspecialchars($row['location']); ?></td>
                    <td style="font-size:12px; color:#94a3b8;"><?php echo htmlspecialchars($row['postingDate']); ?></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-inspect">Analyze Log</a></td>
                </tr>
                <?php $cnt++; } 
                } else { ?>
                    <tr><td colspan="6" style="text-align:center; padding: 60px; color: #64748b;">No tactical logs recovered.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
    // 1. Line Chart Config
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Total Fires',
                data: <?php echo json_encode($counts); ?>,
                borderColor: '#ff6b3d',
                backgroundColor: 'rgba(255, 107, 61, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#fff',
                pointRadius: 5
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b', stepSize: 1 } },
                x: { grid: { display: false }, ticks: { color: '#64748b' } }
            }
        }
    });

    // 2. Radar Chart Config (Expanded Scale)
    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels: ['Electrical', 'Forest', 'Gas/LPG', 'Vehicle', 'Structural'],
            datasets: [{
                label: 'Threat Count',
                data: <?php echo json_encode($radarValues); ?>,
                backgroundColor: 'rgba(168, 85, 247, 0.2)',
                borderColor: '#a855f7',
                pointBackgroundColor: '#a855f7',
                borderWidth: 3
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: 12 },
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    angleLines: { color: 'rgba(255,255,255,0.1)' },
                    grid: { color: 'rgba(255,255,255,0.1)' },
                    suggestedMin: 0,
                    suggestedMax: 5,
                    pointLabels: { color: '#94a3b8', font: { family: 'Orbitron', size: 12 } },
                    ticks: { display: false, backdropColor: 'transparent' }
                }
            }
        }
    });
    </script>
</body>
</html>
<?php } ?>