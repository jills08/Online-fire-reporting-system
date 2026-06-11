<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['aid']==0)) { header('location:logout.php'); } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OFRS | Search Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --line: rgba(124, 161, 255, 0.1); }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; }
        .sidebar { width: 260px; background: rgba(4, 10, 20, 0.95); border-right: 1px solid var(--line); padding: 20px; min-height:100vh; }
        .main { flex: 1; padding: 40px; }
        .glass-table { width: 100%; border-collapse: collapse; background: var(--glass); border-radius: 20px; border: 1px solid var(--line); }
        .glass-table th { background: rgba(255,255,255,0.05); padding: 20px; text-align: left; font-family: 'Orbitron'; color: var(--orange); font-size: 12px; }
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); }
        .btn-view { padding: 8px 16px; background: var(--orange); color: white; border-radius: 8px; text-decoration: none; font-size: 12px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3 style="font-family:'Orbitron'; color:var(--orange);">OFRS COMMAND</h3>
        <a href="search-report.php" style="color:#99a7c2; text-decoration:none;"><i class="fas fa-arrow-left"></i> New Search</a>
    </div>
    <div class="main">
        <?php $searchdata=$_POST['searchdata']; ?>
        <h1 style="font-family:'Orbitron';">Results for: "<?php echo $searchdata;?>"</h1>
        <table class="glass-table">
            <thead><tr><th>#</th><th>Name</th><th>Mobile</th><th>Location</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php 
                $query=mysqli_query($con,"select * from tblfirereport where FullName like '%$searchdata%' or MobileNumber like '%$searchdata%' or Location like '%$searchdata%'");
                $cnt=1;
                while($row=mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $row['FullName']; ?></td>
                    <td><?php echo $row['MobileNumber']; ?></td>
                    <td><?php echo $row['Location']; ?></td>
                    <td><b style="color:var(--orange)"><?php echo ($row['Status']==""?"New":$row['Status']);?></b></td>
                    <td><a href="request-details.php?requestid=<?php echo $row['id'];?>" class="btn-view">Details</a></td>
                </tr>
                <?php $cnt++; } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>