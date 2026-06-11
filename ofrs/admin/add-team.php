<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Added ../ to correctly find the config file
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['submit'])) {
        // Sanitize inputs to prevent SQL errors
        $tname = mysqli_real_escape_string($con, $_POST['teamname']);
        $tlname = mysqli_real_escape_string($con, $_POST['teamleadname']);
        $mobno = mysqli_real_escape_string($con, $_POST['mobilenumber']);
        $tmember = mysqli_real_escape_string($con, $_POST['teammember']);

        $query = mysqli_query($con, "insert into tblteams(teamName,teamLeaderName,teamLeadMobno,teamMembers) values('$tname','$tlname','$mobno','$tmember')");
        
        if($query){ 
            echo "<script>alert('Fire Rescue Team Registered Successfully');</script>"; 
            echo "<script>window.location.href='manage-teams.php';</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Add Rescue Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.8); --orange: #ff6b3d; --line: rgba(124, 161, 255, 0.1); }
        body { 
            background: var(--bg); color: white; font-family: 'Inter', sans-serif; 
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin:0;
            background: radial-gradient(circle at bottom left, #1e293b, #020617);
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
            width: 100%; padding: 16px; background: linear-gradient(135deg, #ff4d4d, #ff6b3d); 
            border: none; border-radius: 12px; color: white; font-weight: bold; 
            cursor: pointer; text-transform: uppercase; margin-top: 15px; font-family: 'Orbitron'; letter-spacing: 1px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,107,61,0.3); }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Register Team</h2>
        <form method="post">
            <div class="field">
                <label>Team Designation (e.g. Squad Alpha)</label>
                <input type="text" name="teamname" required placeholder="Enter team name">
            </div>
            <div class="field">
                <label>Leader Name</label>
                <input type="text" name="teamleadname" required placeholder="Enter name of team lead">
            </div>
            <div class="field">
                <label>Emergency Contact</label>
                <input type="text" name="mobilenumber" maxlength="10" required placeholder="10-digit mobile number">
            </div>
            <div class="field">
                <label>Members (Comma Separated)</label>
                <input type="text" name="teammember" required placeholder="John, Doe, Smith...">
            </div>
            <button type="submit" name="submit" class="btn">Initialize Team</button>
            <div style="text-align:center; margin-top:20px;">
                <a href="manage-teams.php" style="color:#94a3b8; text-decoration:none; font-size:13px;">← Cancel & Return</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php } ?>