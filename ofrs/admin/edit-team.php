<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Added ../ to find the config file in the root directory
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    // Handle the Update logic
    if(isset($_POST['update'])) {
        $tid = intval($_GET['teamid']);
        $tname = mysqli_real_escape_string($con, $_POST['teamname']);
        $tlname = mysqli_real_escape_string($con, $_POST['teamleadname']);
        $mobno = mysqli_real_escape_string($con, $_POST['mobilenumber']);
        $tmember = mysqli_real_escape_string($con, $_POST['teammember']);

        $query = mysqli_query($con, "update tblteams set teamName='$tname', teamLeaderName='$tlname', teamLeadMobno='$mobno', teamMembers='$tmember' where id='$tid'");
        
        if($query){ 
            echo "<script>alert('Team Intelligence Updated Successfully');</script>"; 
            echo "<script>window.location.href='manage-teams.php';</script>";
        } else {
            echo "<script>alert('Update failed. Please check system logs.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Edit Rescue Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.8); --orange: #ffc857; --line: rgba(124, 161, 255, 0.1); }
        body { 
            background: var(--bg); color: white; font-family: 'Inter', sans-serif; 
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin:0;
            background: radial-gradient(circle at top left, #1e293b, #020617);
        }
        .form-card { 
            background: var(--glass); padding: 40px; border-radius: 25px; 
            border: 1px solid var(--line); width: 100%; max-width: 500px; 
            backdrop-filter: blur(10px); box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        h2 { font-family: 'Orbitron'; color: var(--orange); text-align: center; margin-bottom: 30px; letter-spacing: 2px; }
        .field { margin-bottom: 20px; }
        label { display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; margin-bottom: 8px; font-weight: 600; }
        input { 
            width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--line); 
            border-radius: 12px; color: white; outline: none; box-sizing: border-box; transition: 0.3s;
        }
        input:focus { border-color: var(--orange); background: rgba(0,0,0,0.5); }
        .btn { 
            width: 100%; padding: 16px; background: linear-gradient(135deg, #ffc857, #ff9f43); 
            border: none; border-radius: 12px; color: #000; font-weight: 800; 
            cursor: pointer; text-transform: uppercase; margin-top: 15px; font-family: 'Orbitron'; letter-spacing: 1px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,200,87,0.3); }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Modify Team</h2>
        <form method="post">
            <?php 
            $tid = intval($_GET['teamid']);
            $ret = mysqli_query($con, "select * from tblteams where id='$tid'");
            while($row = mysqli_fetch_array($ret)) {
            ?>
            <div class="field">
                <label>Team Designation</label>
                <input type="text" name="teamname" value="<?php echo $row['teamName'];?>" required>
            </div>
            <div class="field">
                <label>Leader Name</label>
                <input type="text" name="teamleadname" value="<?php echo $row['teamLeaderName'];?>" required>
            </div>
            <div class="field">
                <label>Emergency Contact</label>
                <input type="text" name="mobilenumber" value="<?php echo $row['teamLeadMobno'];?>" maxlength="10" required>
            </div>
            <div class="field">
                <label>Members</label>
                <input type="text" name="teammember" value="<?php echo $row['teamMembers'];?>" required>
            </div>
            <button type="submit" name="update" class="btn">Update Records</button>
            <?php } ?>
            <div style="text-align:center; margin-top:20px;">
                <a href="manage-teams.php" style="color:#94a3b8; text-decoration:none; font-size:13px;">← Discard Changes</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php } ?>