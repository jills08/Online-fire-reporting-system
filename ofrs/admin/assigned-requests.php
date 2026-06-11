<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX 1: Corrected path to config
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Assigned Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --blue: #00d2ff; --line: rgba(124, 161, 255, 0.1); }
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
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; font-size: 12px; color: var(--blue); }
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; }
        .glass-table tr:hover { background: rgba(255,255,255,0.02); }

        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; background: rgba(0,210,255,0.2); color: var(--blue); }
        .btn-view { padding: 8px 16px; background: var(--orange); color: white; border-radius: 8px; text-decoration: none; font-size: 11px; font-weight: bold; font-family: 'Orbitron'; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>OFRS COMMAND</h3>
        <a href="dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="new-requests.php" class="nav-link"><i class="fas fa-fire"></i> New Requests</a>
        <a href="all-requests.php" class="nav-link"><i class="fas fa-list"></i> All Reports</a>
    </div>

    <div class="main">
        <h1 style="font-family:'Orbitron'; margin-bottom:30px; color: var(--blue);">Assigned Active Reports</h1>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Assigned Team</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // FIX 2: JOIN query to get Team Name and Filter for Assigned status
                $sql = "SELECT tblfirereport.*, tblteams.teamName 
                        FROM tblfirereport 
                        JOIN tblteams ON tblteams.id = tblfirereport.assignTo 
                        WHERE tblfirereport.status = 'Assigned' 
                        OR tblfirereport.status = 'Team on the Way' 
                        OR tblfirereport.status = 'Fire Relief in Progress'
                        ORDER BY tblfirereport.id DESC";
                
                $query = mysqli_query($con, $sql);
                $cnt = 1;
                while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo $row['fullName'] ?? $row['FullName'] ?? 'N/A'; ?></b></td>
                    <td style="color:var(--orange); font-weight:bold;"><?php echo $row['teamName']; ?></td>
                    <td style="font-size:12px; color:#94a3b8;"><?php echo $row['postingDate'] ?? $row['PostingDate'] ?? 'N/A'; ?></td>
                    <td><span class="status-pill"><?php echo $row['status']; ?></span></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Details</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>