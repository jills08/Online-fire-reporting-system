<?php
session_start();
// Database connection
include_once('../includes/config.php');

if (isset($_POST['login'])) {
    $uname = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5($_POST['inputpwd']);

    // Query to validate admin credentials
    $query = mysqli_query($con, "SELECT ID, AdminName FROM tbladmin WHERE AdminuserName='$uname' AND Password='$password'");
    $ret = mysqli_fetch_array($query);

    if ($ret > 0) {
        // Set session variables upon successful login
        $_SESSION['aid'] = $ret['ID'];
        $_SESSION['uname'] = $ret['AdminName'];
        header('location:dashboard.php');
    } else {
        echo "<script>alert('Access Denied: Invalid Credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFRS Admin | Secure Uplink</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #ff6b3d; 
            --primary-glow: rgba(255, 107, 61, 0.35);
            --bg: #050b14;
            --glass: rgba(15, 23, 42, 0.8); 
            --border: rgba(255, 107, 61, 0.2); 
        }

        body { 
            margin: 0; padding: 0; font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #1e293b, #020617);
            height: 100vh; display: flex; align-items: center; justify-content: center; color: white;
            overflow: hidden;
        }

        /* Decorative background elements */
        body::before {
            content: ""; position: absolute; width: 300px; height: 300px;
            background: var(--primary); filter: blur(150px);
            top: 10%; left: 10%; opacity: 0.1; z-index: -1;
        }

        .login-card {
            background: var(--glass); backdrop-filter: blur(20px);
            border: 1px solid var(--border); padding: 50px 40px; border-radius: 28px;
            width: 100%; max-width: 420px; box-shadow: 0 25px 50px rgba(0,0,0,0.6);
            text-align: center; position: relative;
        }

        .brand-icon {
            width: 60px; height: 60px; background: var(--primary);
            border-radius: 18px; display: inline-flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 20px; box-shadow: 0 0 20px var(--primary-glow);
        }

        h2 { 
            font-family: 'Orbitron', sans-serif; color: var(--primary); 
            text-transform: uppercase; letter-spacing: 3px; margin: 0 0 10px 0;
        }

        p.subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 35px; }

        .input-group { margin-bottom: 25px; text-align: left; }

        label { 
            display: block; font-size: 11px; text-transform: uppercase; 
            color: #64748b; margin-bottom: 8px; letter-spacing: 1px; font-weight: 600;
        }

        .input-wrapper { position: relative; }

        .input-wrapper i {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
            color: #475569; font-size: 14px;
        }

        input { 
            width: 100%; padding: 14px 14px 14px 45px; background: rgba(0,0,0,0.4); 
            border: 1px solid var(--border); border-radius: 14px; color: white; 
            outline: none; box-sizing: border-box; transition: 0.3s; font-size: 15px;
        }

        input:focus { 
            border-color: var(--primary); 
            box-shadow: 0 0 15px var(--primary-glow);
            background: rgba(0,0,0,0.6);
        }

        .btn-login {
            width: 100%; padding: 16px; 
            background: linear-gradient(135deg, #ff4d4d, #ff6b3d);
            border: none; border-radius: 14px; color: white; font-weight: 800;
            cursor: pointer; text-transform: uppercase; letter-spacing: 2px; 
            transition: 0.3s; margin-top: 10px; font-family: 'Orbitron', sans-serif;
        }

        .btn-login:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 12px 25px var(--primary-glow); 
        }

        .footer-links { margin-top: 30px; display: flex; justify-content: center; gap: 20px; }

        .footer-links a { 
            color: #64748b; text-decoration: none; font-size: 13px; transition: 0.3s; 
        }

        .footer-links a:hover { color: var(--primary); }

    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h2>Admin Access</h2>
        <p class="subtitle">Enter credentials for secure terminal uplink</p>

        <form method="post">
            <div class="input-group">
                <label>System Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" placeholder="Admin ID" required autocomplete="off">
                </div>
            </div>

            <div class="input-group">
                <label>Security Key</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="inputpwd" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" name="login" class="btn-login">Authorize Link</button>
        </form>

        <div class="footer-links">
            <a href="../index.php"><i class="fas fa-home"></i> Home</a>
            <a href="#"><i class="fas fa-key"></i> Recovery</a>
        </div>
    </div>

</body>
</html>