<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['aid']==0)) { header('location:logout.php'); } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OFRS | Active Relief Work</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ffc857; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; }
        .sidebar { width: 260px; background: rgba(4, 10, 20, 0.95); border-right: 1px solid var(--line); padding: 20px; min-height:100vh; }
        .main { flex: 1; padding: 40px; }
        .glass-table { width: 100%; border-collapse: collapse; background: var(--glass); border-radius: 20px; overflow: hidden; border: 1px solid var(--line); }
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; color: #ff8f4d; font-size: 12px; }
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; }
        .status-pill { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; background: rgba(255,143,77,0.2); color: #ff8f4d; text-transform: uppercase; }
        .btn-view { padding: 8px 16px; background: #ff6b3d; color: white; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="font-family:'Orbitron'; color:#ff6b3d;">OFRS COMMAND</h3>
        <a href="dashboard.php" style="color:#99a7c2; text-decoration:none;"><i class="fas fa-arrow-left"></i> Dashboard</a>
    </div>
    <div class="main">
        <h1 style="font-family:'Orbitron';">Relief Operations in Progress</h1>
        <table class="glass-table">
            <thead><tr><th>#</th><th>Reporter</th><th>Team</th><th>Location</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php 
                $query=mysqli_query($con,"select * from tblfirereport where status='Fire Relief Work in Progress'");
                $cnt=1;
                while($row=mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $row['FullName']; ?></td>
                    <td><b style="color:white;"><?php echo $row['assignTo']; ?></b></td>
                    <td><?php echo $row['Location']; ?></td>
                    <td><span class="status-pill">In Progress</span></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Live Update</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>