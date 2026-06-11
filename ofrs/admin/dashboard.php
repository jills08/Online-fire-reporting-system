<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../includes/config.php');

// Security Check
if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFRS | Command Center</title>
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
            --line: rgba(124, 161, 255, 0.1);
        }

        body { 
            margin: 0; background: var(--bg); color: #eaf2ff; 
            font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; 
        }
        
        /* Main Content Area Adjustment for Sidebar */
        .main { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }

        .header { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 40px; padding-bottom: 20px; border-bottom: 1px solid var(--line);
        }

        .header h1 { font-family: 'Orbitron'; font-size: 24px; margin: 0; letter-spacing: 1px; }

        /* Stats Grid */
        .stats-grid { 
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; 
        }

        .stat-card { 
            background: var(--glass); backdrop-filter: blur(15px); padding: 30px; 
            border-radius: 24px; border: 1px solid var(--line); position: relative; 
            overflow: hidden; text-decoration: none; color: inherit; transition: 0.4s;
        }

        .stat-card:hover { 
            transform: translateY(-8px); border-color: var(--orange); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }

        .stat-card h4 { 
            margin: 0; color: #94a3b8; text-transform: uppercase; 
            font-size: 11px; letter-spacing: 1.5px; font-weight: 700;
        }

        .stat-card .value { 
            font-family: 'Orbitron'; font-size: 36px; margin-top: 15px; 
            display: block; font-weight: 700;
        }

        .stat-card i { 
            position: absolute; right: -15px; bottom: -15px; 
            font-size: 90px; opacity: 0.05; color: white; 
        }

        /* Border Glows */
        .new { border-top: 4px solid var(--red); }
        .assigned { border-top: 4px solid var(--orange); }
        .completed { border-top: 4px solid var(--green); }

        .welcome-msg { color: var(--orange); font-weight: 600; }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <div class="header">
            <div>
                <h1>Command Center</h1>
                <p style="color: #64748b; font-size: 14px; margin-top: 5px;">Welcome back, <span class="welcome-msg"><?php echo h($_SESSION['uname']); ?></span></p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 14px; color: #94a3b8;"><?php echo date('l, d F Y'); ?></div>
                <div style="font-size: 12px; color: var(--orange); margin-top: 4px;">System Online • Secure Session</div>
            </div>
        </div>

        <div class="stats-grid">
            <?php 
            // Total Reports
            $q1 = mysqli_query($con, "SELECT id FROM tblfirereport");
            $total = mysqli_num_rows($q1);
            ?>
            <a href="all-requests.php" class="stat-card">
                <h4>Total Incidents</h4>
                <span class="value"><?php echo $total; ?></span>
                <i class="fas fa-fire-extinguisher"></i>
            </a>

            <?php 
            // New Reports
            $q2 = mysqli_query($con, "SELECT id FROM tblfirereport WHERE status IS NULL OR status='' OR status='Reported'");
            $new = mysqli_num_rows($q2);
            ?>
            <a href="new-requests.php" class="stat-card new">
                <h4>New Emergencies</h4>
                <span class="value" style="color: var(--red);"><?php echo $new; ?></span>
                <i class="fas fa-bell"></i>
            </a>

            <?php 
            // Assigned / In Progress
            $q3 = mysqli_query($con, "SELECT id FROM tblfirereport WHERE status IN ('Assigned', 'Team on the Way', 'Fire Relief in Progress')");
            $assigned = mysqli_num_rows($q3);
            ?>
            <a href="assigned-requests.php" class="stat-card assigned">
                <h4>Unit Assigned</h4>
                <span class="value" style="color: var(--orange);"><?php echo $assigned; ?></span>
                <i class="fas fa-truck-moving"></i>
            </a>

            <?php 
            // Resolved
            $q4 = mysqli_query($con, "SELECT id FROM tblfirereport WHERE status='Request Completed'");
            $comp = mysqli_num_rows($q4);
            ?>
            <a href="completed-requests.php" class="stat-card completed">
                <h4>Resolved Cases</h4>
                <span class="value" style="color: var(--green);"><?php echo $comp; ?></span>
                <i class="fas fa-check-double"></i>
            </a>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: rgba(255,255,255,0.03); border-radius: 15px; border: 1px dashed var(--line); font-size: 13px; color: #64748b;">
            <i class="fas fa-info-circle" style="color: var(--orange); margin-right: 8px;"></i> 
            Real-time telemetry is active. Secure uplink established at <?php echo date('H:i'); ?> HRS.
        </div>
    </div>

</body>
</html>
<?php } ?>