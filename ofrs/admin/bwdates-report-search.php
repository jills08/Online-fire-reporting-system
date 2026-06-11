<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Analytics Range</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --purple: #a855f7; --line: rgba(124, 161, 255, 0.1); }
        
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; min-height: 100vh; }
        
        /* Layout Fix for Sidebar */
        .main { flex: 1; padding: 40px; margin-left: 260px; display: flex; flex-direction: column; align-items: center; justify-content: center; }

        .search-container { 
            background: var(--glass); backdrop-filter: blur(20px); padding: 40px; 
            border-radius: 24px; border: 1px solid var(--line); width: 100%; max-width: 500px; 
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        h2 { font-family: 'Orbitron'; color: var(--purple); text-align: center; margin-bottom: 30px; letter-spacing: 2px; }

        .form-group { margin-bottom: 25px; }
        
        label { 
            display: block; margin-bottom: 10px; font-size: 11px; 
            font-family: 'Orbitron'; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px;
        }

        input[type="date"] { 
            width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--line); 
            border-radius: 12px; color: white; outline: none; font-family: 'Inter'; font-size: 14px;
            transition: 0.3s; color-scheme: dark; /* Forces dark theme on browser picker */
        }
        
        input[type="date"]:focus { border-color: var(--purple); background: rgba(168, 85, 247, 0.05); }

        .btn-generate { 
            width: 100%; padding: 16px; background: var(--purple); color: white; border: none; 
            border-radius: 12px; font-family: 'Orbitron'; cursor: pointer; 
            font-weight: bold; transition: 0.3s; letter-spacing: 1px; margin-top: 10px;
        }
        
        .btn-generate:hover { 
            background: #9333ea; transform: translateY(-2px); 
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.3); 
        }

        .info-pill { 
            display: inline-block; padding: 5px 12px; background: rgba(168, 85, 247, 0.1); 
            color: var(--purple); border-radius: 50px; font-size: 10px; font-weight: bold; margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <?php include_once('includes/sidebar.php'); ?>

    <div class="main">
        <div class="search-container">
            <div style="text-align:center;"><span class="info-pill">Temporal Intelligence</span></div>
            <h2>ANALYTICS RANGE</h2>
            
            <form method="post" action="bwdates-report-ds.php">
                <div class="form-group">
                    <label><i class="fas fa-history" style="margin-right: 8px;"></i> Initial Date (From)</label>
                    <input type="date" name="fromdate" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-hourglass-end" style="margin-right: 8px;"></i> Final Date (To)</label>
                    <input type="date" name="todate" required>
                </div>

                <button type="submit" name="submit" class="btn-generate">
                    <i class="fas fa-microchip"></i> ANALYZE TEMPORAL DATA
                </button>
            </form>
            
            <p style="text-align:center; color:#64748b; font-size:12px; margin-top:20px;">
                Filter incident logs based on tactical submission timestamps.
            </p>
        </div>
    </div>

</body>
</html>
<?php } ?>