<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Corrected path to config
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {

    // DELETE LOGIC: Safeguard with isset()
    if (isset($_GET['action']) && $_GET['action'] == 'delete') {
        $id = intval($_GET['id']);
        $query = mysqli_query($con, "DELETE FROM tblteams WHERE id='$id'");
        if ($query) {
            echo "<script>alert('TACTICAL REMOVAL COMPLETE: Team data purged.');</script>";
            echo "<script>window.location.href='manage-teams.php';</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Manage Teams</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --red: #ff5d73; --line: rgba(124, 161, 255, 0.1); }
        
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
            font-family: 'Orbitron'; font-size: 11px; color: var(--orange); letter-spacing: 1.5px;
        }
        
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; color: #cbd5e1; }
        .glass-table tr:hover { background: rgba(255,255,255,0.03); }

        .btn-add { 
            display: inline-flex; align-items: center; gap: 10px; padding: 14px 28px; 
            background: linear-gradient(135deg, #ff4d4d, #ff6b3d); 
            color: white; border-radius: 14px; text-decoration: none; margin-bottom: 30px; 
            font-weight: bold; font-family: 'Orbitron'; font-size: 12px; transition: 0.3s;
            box-shadow: 0 4px 15px rgba(255,107,61,0.2);
        }
        
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(255,107,61,0.4); }
        
        .action-icons a { margin-right: 15px; text-decoration: none; }
        .action-icons i { font-size: 18px; transition: 0.3s; }
        .fa-edit { color: #ffc857; opacity: 0.7; }
        .fa-trash { color: var(--red); opacity: 0.7; }
        .action-icons i:hover { transform: scale(1.2); opacity: 1; }
        
        .team-badge { 
            background: rgba(255, 107, 61, 0.1); color: var(--orange); 
            padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;
        }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <header style="margin-bottom: 40px;">
            <h1 style="font-family:'Orbitron'; margin:0; font-size: 28px; letter-spacing: 1px;">Response Units</h1>
            <p style="color: #64748b; margin-top: 5px;">Management and deployment of active fire rescue teams.</p>
        </header>

        <a href="add-team.php" class="btn-add"><i class="fas fa-plus"></i> Register Tactical Unit</a>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>Designation</th>
                    <th>Commanding Officer</th>
                    <th>Emergency Comms</th>
                    <th>Tactical Control</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = mysqli_query($con, "SELECT * FROM tblteams ORDER BY teamName ASC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><span class="team-badge"><?php echo h($row['teamName']); ?></span></td>
                    <td><b style="color:white;"><?php echo h($row['teamLeaderName']); ?></b></td>
                    <td><i class="fas fa-phone-alt" style="font-size: 12px; color: #64748b; margin-right: 8px;"></i><?php echo h($row['teamLeadMobno']); ?></td>
                    <td class="action-icons">
                        <a href="edit-team.php?teamid=<?php echo $row['id'];?>" title="Edit Unit"><i class="fas fa-edit"></i></a>
                        <a href="manage-teams.php?id=<?php echo $row['id'];?>&action=delete" onclick="return confirm('WARNING: DEPLOYMENT DATA FOR THIS TEAM WILL BE PURGED. PROCEED?');" title="Purge Unit"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php } } else { ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 40px; color: #64748b;">No active rescue units detected in the system.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php } ?>