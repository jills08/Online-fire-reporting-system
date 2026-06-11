<?php 
// Standard Session check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Added ../ to look into the root includes folder
include_once('../includes/config.php');

// Security check: Redirect to login if session is empty
if (empty($_SESSION['aid'])) { 
    header('location:index.php'); 
    exit;
} else {
    // Handle Profile Update
    if(isset($_POST['update'])) {
        $adminid = $_SESSION['aid'];
        $aname = mysqli_real_escape_string($con, $_POST['adminname']);
        $mobno = mysqli_real_escape_string($con, $_POST['mobilenumber']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        
        $query = mysqli_query($con, "UPDATE tbladmin SET AdminName='$aname', MobileNumber='$mobno', Email='$email' WHERE ID='$adminid'");
        
        if ($query) { 
            echo '<script>alert("Profile Updated Successfully")</script>'; 
            echo "<script>window.location.href ='profile.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Admin Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.8); --orange: #ff6b3d; --line: rgba(124, 161, 255, 0.1); }
        body { 
            background: var(--bg); color: white; font-family: 'Inter', sans-serif; 
            display: flex; align-items: center; justify-content: center; 
            min-height: 100vh; margin:0;
            background: radial-gradient(circle at top right, #1e293b, #020617);
        }
        .form-card { 
            background: var(--glass); padding: 40px; border-radius: 25px; 
            border: 1px solid var(--line); width: 100%; max-width: 450px; 
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
        input[readonly] { background: rgba(255,255,255,0.05); cursor: not-allowed; }
        .btn { 
            width: 100%; padding: 15px; background: linear-gradient(135deg, #ff4d4d, #ff6b3d); 
            border: none; border-radius: 12px; color: white; font-weight: bold; 
            cursor: pointer; text-transform: uppercase; margin-top: 10px; transition: 0.3s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(255,107,61,0.3); }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Admin Profile</h2>
        <form method="post">
            <?php 
            $adminid = $_SESSION['aid'];
            $ret = mysqli_query($con, "SELECT * FROM tbladmin WHERE ID='$adminid'");
            while($row = mysqli_fetch_array($ret)) { 
            ?>
                <div class="field">
                    <label>Admin Name</label>
                    <input type="text" name="adminname" value="<?php echo $row['AdminName'];?>" required>
                </div>
                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $row['Email'];?>" required>
                </div>
                <div class="field">
                    <label>Contact Number</label>
                    <input type="text" name="mobilenumber" value="<?php echo $row['MobileNumber'];?>" maxlength="10" required>
                </div>
                <div class="field">
                    <label>System Username (Read-only)</label>
                    <input type="text" value="<?php echo $row['AdminuserName'];?>" readonly>
                </div>
                <button type="submit" name="update" class="btn">Update Command Profile</button>
            <?php } ?>
            <div style="text-align:center; margin-top:20px;">
                <a href="dashboard.php" style="color:#94a3b8; text-decoration:none; font-size:13px;">← Return to Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php } ?>