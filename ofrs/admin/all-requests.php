<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Path to config
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Master Incident Logs</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --bg: #050b14; 
            --glass: rgba(16, 32, 58, 0.7); 
            --orange: #ff6b3d; 
            --red: #ff5d73; 
            --yellow: #ffc857;
            --green: #59d39a;
            --blue: #00d2ff;
            --line: rgba(124, 161, 255, 0.1); 
        }
        
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        /* Main Content Area Adjustment for Sidebar */
        .main { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }

        .glass-table { 
            width: 100%; border-collapse: collapse; background: var(--glass); 
            backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid var(--line); 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .glass-table th { 
            background: rgba(255,255,255,0.05); padding: 20px; text-align: left; 
            font-family: 'Orbitron'; font-size: 11px; color: var(--blue); letter-spacing: 1.5px;
        }
        
        .glass-table td { padding: 18px 20px; border-bottom: 1px solid var(--line); font-size: 14px; color: #cbd5e1; }
        .glass-table tr:hover { background: rgba(255,255,255,0.03); }

        /* Tactical Status Pills */
        .status-pill { 
            padding: 5px 12px; border-radius: 20px; font-size: 10px; 
            font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .st-new { background: rgba(255, 93, 115, 0.2); color: var(--red); }
        .st-assigned { background: rgba(255, 107, 61, 0.2); color: var(--orange); }
        .st-progress { background: rgba(255, 200, 87, 0.2); color: var(--yellow); }
        .st-completed { background: rgba(89, 211, 154, 0.2); color: var(--green); }

        .btn-view { 
            padding: 8px 16px; background: rgba(0, 210, 255, 0.1); color: var(--blue); 
            border: 1px solid var(--blue); border-radius: 8px; text-decoration: none; 
            font-size: 11px; font-weight: bold; font-family: 'Orbitron'; transition: 0.3s;
        }
        .btn-view:hover { background: var(--blue); color: #000; box-shadow: 0 0 15px rgba(0, 210, 255, 0.3); }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <header style="margin-bottom: 40px;">
            <h1 style="font-family:'Orbitron'; margin:0; font-size: 28px; letter-spacing: 1px;">Incident Master Logs</h1>
            <p style="color: #64748b; margin-top: 5px;">Historical record of all emergency broadcasts and tactical responses.</p>
        </header>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Tactical Status</th>
                    <th>Log Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Fetching all reports from tblfirereport
                $query = mysqli_query($con, "SELECT * FROM tblfirereport ORDER BY id DESC");
                $cnt = 1;
                while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo h($row['fullName'] ?? $row['FullName'] ?? 'N/A'); ?></b></td>
                    <td><?php echo h($row['mobileNumber'] ?? $row['MobileNumber'] ?? 'N/A'); ?></td>
                    <td><i class="fas fa-map-marker-alt" style="color:#64748b; font-size:12px; margin-right:5px;"></i> <?php echo h($row['location'] ?? $row['Location'] ?? 'N/A'); ?></td>
                    <td>
                        <?php 
                        $st = strtolower($row['status'] ?? $row['Status'] ?? '');
                        if($st == '' || $st == 'reported') { 
                            echo "<span class='status-pill st-new'>New Alert</span>"; 
                        } elseif($st == 'assigned') { 
                            echo "<span class='status-pill st-assigned'>Assigned</span>"; 
                        } elseif($st == 'team on the way' || $st == 'fire relief in progress') { 
                            echo "<span class='status-pill st-progress'>Active</span>"; 
                        } elseif($st == 'request completed') { 
                            echo "<span class='status-pill st-completed'>Resolved</span>"; 
                        } else {
                            echo "<span class='status-pill' style='background:rgba(255,255,255,0.1);'>$st</span>";
                        }
                        ?>
                    </td>
                    <td style="font-size:12px; color:#94a3b8;"><?php echo $row['postingDate'] ?? $row['PostingDate'] ?? 'N/A'; ?></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Inspect</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>