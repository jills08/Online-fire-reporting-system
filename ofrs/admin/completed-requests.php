<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Correct path to config
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Resolved Missions</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --green: #00ff88; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: rgba(4, 10, 20, 0.95); border-right: 1px solid var(--line); padding: 20px; }
        .sidebar h3 { font-family: 'Orbitron'; color: var(--orange); font-size: 18px; margin-bottom: 40px; }
        .nav-link { display: block; padding: 12px; color: #99a7c2; text-decoration: none; border-radius: 12px; margin-bottom: 8px; transition: 0.3s; }
        .nav-link:hover { background: rgba(0,255,136,0.1); color: white; border: 1px solid var(--green); }

        .main { flex: 1; padding: 40px; }
        .glass-table { 
            width: 100%; border-collapse: collapse; background: var(--glass); 
            backdrop-filter: blur(10px); border-radius: 20px; overflow: hidden; border: 1px solid var(--line); 
        }
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; font-size: 12px; color: var(--green); }
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; }
        .glass-table tr:hover { background: rgba(0,255,136,0.02); }

        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: rgba(0,255,136,0.2); color: var(--green); }
        .btn-view { padding: 8px 16px; background: var(--glass); color: white; border: 1px solid var(--green); border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: bold; font-family: 'Orbitron'; transition: 0.3s; }
        .btn-view:hover { background: var(--green); color: #000; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>OFRS COMMAND</h3>
        <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="all-requests.php" class="nav-link"><i class="fas fa-list"></i> All Reports</a>
        <a href="logout.php" class="nav-link" style="margin-top:50px; color:#ff4d4d;"><i class="fas fa-power-off"></i> Logout</a>
    </div>

    <div class="main">
        <h1 style="font-family:'Orbitron'; margin-bottom:30px; color: var(--green);">Resolved Missions</h1>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Resolved By</th>
                    <th>Completion Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Query: Join to get the Team Name and filter for 'Request Completed'
                $sql = "SELECT tblfirereport.*, tblteams.teamName 
                        FROM tblfirereport 
                        LEFT JOIN tblteams ON tblteams.id = tblfirereport.assignTo 
                        WHERE tblfirereport.status = 'Request Completed' 
                        ORDER BY tblfirereport.id DESC";
                
                $query = mysqli_query($con, $sql);
                $cnt = 1;
                while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo $row['fullName'] ?? $row['FullName'] ?? 'N/A'; ?></b></td>
                    <td style="color:var(--green); font-weight:bold;"><?php echo $row['teamName'] ?? 'Internal Team'; ?></td>
                    <td style="font-size:12px; color:#94a3b8;"><?php echo $row['postingDate'] ?? $row['PostingDate'] ?? 'N/A'; ?></td>
                    <td><span class="status-pill">Resolved</span></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Archive View</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>