<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// FIX: Path to config
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Intelligence Trace</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --blue: #00d2ff; --line: rgba(124, 161, 255, 0.1); }
        
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        /* Sidebar Adjustment */
        .main { flex: 1; padding: 40px; margin-left: 260px; overflow-y: auto; }

        .search-box { 
            background: var(--glass); padding: 30px; border-radius: 20px; 
            border: 1px solid var(--line); margin-bottom: 30px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .search-input-wrapper { display: flex; gap: 15px; }

        input[type="text"] { 
            flex: 1; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--line); 
            border-radius: 12px; color: white; outline: none; font-family: 'Inter'; font-size: 16px;
            transition: 0.3s;
        }
        
        input[type="text"]:focus { border-color: var(--blue); background: rgba(0, 210, 255, 0.05); box-shadow: 0 0 15px rgba(0, 210, 255, 0.1); }

        .btn-search { 
            padding: 15px 35px; background: var(--orange); color: white; border: none; 
            border-radius: 12px; font-family: 'Orbitron'; cursor: pointer; 
            font-weight: bold; transition: 0.3s; letter-spacing: 1px;
        }
        
        .btn-search:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(255, 107, 61, 0.4); }

        .glass-table { 
            width: 100%; border-collapse: collapse; background: var(--glass); 
            border-radius: 20px; overflow: hidden; border: 1px solid var(--line); 
        }
        
        .glass-table th { 
            background: rgba(255,255,255,0.05); padding: 20px; text-align: left; 
            font-family: 'Orbitron'; font-size: 11px; color: var(--blue); letter-spacing: 1.5px;
        }
        
        .glass-table td { padding: 20px; border-bottom: 1px solid var(--line); font-size: 14px; color: #cbd5e1; }
        .glass-table tr:hover { background: rgba(255,255,255,0.03); }
        
        .status-text { font-family: 'Orbitron'; font-size: 10px; letter-spacing: 1px; }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <header style="margin-bottom: 40px;">
            <h1 style="font-family:'Orbitron'; margin:0; font-size: 28px; color: var(--blue);">Intelligence Trace</h1>
            <p style="color: #64748b; margin-top: 5px;">Cross-reference logs by Reporter Name, Mobile, or Location.</p>
        </header>

        <div class="search-box">
            <form method="post" class="search-input-wrapper">
                <input type="text" name="searchdata" placeholder="Input target keywords (e.g. Andheri, Kushaal, 9876...)" required>
                <button type="submit" name="search" class="btn-search"><i class="fas fa-satellite-dish"></i> Execute Trace</button>
            </form>
        </div>

        <?php if(isset($_POST['search'])) { 
            $sdata = mysqli_real_escape_string($con, $_POST['searchdata']);
        ?>
        <h3 style="font-family:'Orbitron'; font-size:12px; margin-bottom:20px; color: #94a3b8; letter-spacing: 1px;">
            <i class="fas fa-search" style="margin-right: 10px;"></i> RECOVERY RESULTS FOR: <span style="color: var(--orange);">"<?php echo h($sdata); ?>"</span>
        </h3>
        
        <table class="glass-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Reporter</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Search across multiple relevant columns
                $sql = "SELECT * FROM tblfirereport WHERE 
                        fullName LIKE '%$sdata%' OR 
                        mobileNumber LIKE '%$sdata%' OR 
                        location LIKE '%$sdata%'";
                
                $query = mysqli_query($con, $sql);
                $cnt = 1;
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><b style="color:white;"><?php echo h($row['fullName'] ?? $row['FullName'] ?? 'N/A'); ?></b></td>
                    <td><?php echo h($row['mobileNumber'] ?? $row['MobileNumber'] ?? 'N/A'); ?></td>
                    <td><?php echo h($row['location'] ?? $row['Location'] ?? 'N/A'); ?></td>
                    <td>
                        <span class="status-text" style="color:var(--orange);">
                            <?php echo strtoupper($row['status'] ?: 'REPORTED'); ?>
                        </span>
                    </td>
                    <td>
                        <a href="request-details.php?requestid=<?php echo $row['id'];?>" style="color:var(--blue); text-decoration:none; font-family:'Orbitron'; font-size:11px;">
                            <i class="fas fa-file-alt"></i> View Log
                        </a>
                    </td>
                </tr>
                <?php $cnt++; } 
                } else { 
                    echo "<tr><td colspan='6' style='text-align:center; padding: 40px; color: #64748b;'>No matches found in the encrypted database.</td></tr>"; 
                } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</body>
</html>
<?php } ?>