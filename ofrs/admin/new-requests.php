<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX 1: Added ../ to find the config file
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | New Emergency Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --red: #ff5d73; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: rgba(4, 10, 20, 0.95); border-right: 1px solid var(--line); padding: 20px; }
        .sidebar h3 { font-family: 'Orbitron'; color: var(--orange); font-size: 18px; margin-bottom: 40px; }
        .nav-link { display: block; padding: 12px; color: #99a7c2; text-decoration: none; border-radius: 12px; margin-bottom: 8px; transition: 0.3s; }
        .nav-link:hover { background: rgba(255,107,61,0.1); color: white; border: 1px solid var(--orange); }

        .main { flex: 1; padding: 40px; }
        .glass-table { 
            width: 100%; border-collapse: collapse; background: var(--glass); 
            backdrop-filter: blur(10px); border-radius: 20px; overflow: hidden; border: 1px solid var(--line); 
        }
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; font-size: 12px; color: var(--orange); }
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; }
        .glass-table tr:hover { background: rgba(255,255,255,0.02); }

        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: rgba(255,93,115,0.2); color: var(--red); }
        .btn-view { padding: 8px 16px; background: var(--orange); color: white; border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: bold; font-family: 'Orbitron'; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>OFRS COMMAND</h3>
        <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="all-requests.php" class="nav-link"><i class="fas fa-list"></i> All Reports</a>
        <a href="logout.php" class="nav-link" style="margin-top:50px; color:var(--red);"><i class="fas fa-power-off"></i> Logout</a>
    </div>

    <div class="main">
        <h1 style="font-family:'Orbitron'; margin-bottom:30px; color: var(--red);">New Emergency Requests</h1>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // FIX 2: Selecting only NEW or REPORTED incidents
                $query = mysqli_query($con, "SELECT * FROM tblfirereport WHERE status='' OR status IS NULL OR status='Reported' ORDER BY id DESC");
                $cnt = 1;
                while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo $row['fullName'] ?? $row['FullName'] ?? 'N/A'; ?></b></td>
                    <td><?php echo $row['mobileNumber'] ?? $row['MobileNumber'] ?? 'N/A'; ?></td>
                    <td><?php echo $row['location'] ?? $row['Location'] ?? 'N/A'; ?></td>
                    <td style="font-size:12px; color:#94a3b8;"><?php echo $row['postingDate'] ?? $row['PostingDate'] ?? 'Just now'; ?></td>
                    <td><span class="status-pill">Urgent</span></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Analyze</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>