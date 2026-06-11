<?php session_start();
include_once('includes/config.php');
if (strlen($_SESSION['aid']==0)) { header('location:logout.php'); } else {
    if(isset($_POST['submit'])) {
        $adminid=$_SESSION['aid'];
        $cpassword=md5($_POST['currentpassword']);
        $newpassword=md5($_POST['newpassword']);
        $query=mysqli_query($con,"select ID from tbladmin where ID='$adminid' and Password='$cpassword'");
        if(mysqli_num_rows($query) > 0){
            mysqli_query($con,"update tbladmin set Password='$newpassword' where ID='$adminid'");
            echo '<script>alert("Password Changed Successfully")</script>';
        } else {
            echo '<script>alert("Current Password is Incorrect")</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OFRS | Security Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.8); --orange: #ff5d73; --line: rgba(124, 161, 255, 0.1); }
        body { background: var(--bg); color: white; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin:0; }
        .form-card { background: var(--glass); padding: 40px; border-radius: 25px; border: 1px solid var(--line); width: 100%; max-width: 450px; backdrop-filter: blur(10px); }
        h2 { font-family: 'Orbitron'; color: var(--orange); text-align: center; margin-bottom: 30px; }
        .field { margin-bottom: 20px; }
        label { display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; margin-bottom: 8px; }
        input { width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--line); border-radius: 12px; color: white; box-sizing: border-box; }
        .btn { width: 100%; padding: 15px; background: var(--orange); border: none; border-radius: 12px; color: white; font-weight: bold; cursor: pointer; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Security Uplink</h2>
        <form method="post">
            <div class="field"><label>Current Password</label><input type="password" name="currentpassword" required></div>
            <div class="field"><label>New Password</label><input type="password" name="newpassword" required></div>
            <div class="field"><label>Confirm Password</label><input type="password" name="confirmpassword" required></div>
            <button type="submit" name="submit" class="btn">Update Credentials</button>
            <div style="text-align:center; margin-top:20px;"><a href="dashboard.php" style="color:#94a3b8; text-decoration:none; font-size:13px;">Back to Dashboard</a></div>
        </form>
    </div>
</body>
</html>
<?php } ?>