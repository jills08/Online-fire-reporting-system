<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../includes/config.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:index.php');
} else {
    $rid = intval($_GET['requestid']);

    // Fetch current data to see current status and assigned team
    $current = mysqli_query($con, "SELECT assignTo, status FROM tblfirereport WHERE id='$rid'");
    $cRow = mysqli_fetch_array($current);
    $existingTeam = $cRow['assignTo'];
    $currentStatus = $cRow['status'];

    if(isset($_POST['submit'])) {
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $assign_to = ($existingTeam > 0) ? $existingTeam : mysqli_real_escape_string($con, $_POST['assign_to']);
        $remark = !empty($_POST['remark']) ? mysqli_real_escape_string($con, $_POST['remark']) : "Tactical status update: " . $status;

        $query = mysqli_query($con, "UPDATE tblfirereport SET status='$status', assignTo='$assign_to', assignTme=CURRENT_TIMESTAMP WHERE id='$rid'");
        $history_query = mysqli_query($con, "INSERT INTO tbladminremarks (reportId, status, remark) VALUES ('$rid', '$status', '$remark')");

        if($query && $history_query) {
            echo '<script>alert("Tactical Uplink Successful.");</script>';
            echo "<script>window.location.href ='all-requests.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OFRS | Dispatch Control</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --bg: #050b14; --glass: rgba(16, 32, 58, 0.7); --orange: #ff6b3d; --line: rgba(124, 161, 255, 0.1); --dark-panel: #0d1625; }
        body { margin:0; background: var(--bg); color: #eaf2ff; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .action-card { background: var(--glass); backdrop-filter: blur(15px); padding: 40px; border-radius: 24px; border: 1px solid var(--line); width: 100%; max-width: 450px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        h2 { font-family: 'Orbitron'; color: var(--orange); margin-bottom: 30px; text-align: center; font-size: 20px; letter-spacing: 2px; }
        label { display: block; margin-bottom: 8px; font-size: 11px; font-family: 'Orbitron'; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px; }
        
        /* Dark Select Styling */
        select, textarea { 
            width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--line); 
            border-radius: 10px; color: white; margin-bottom: 25px; outline: none; font-family: 'Inter'; transition: 0.3s;
        }
        
        /* Dark mode dropdown list */
        select option { background-color: var(--dark-panel); color: #eaf2ff; padding: 10px; }
        select option:disabled { color: #4b5563; }

        select:disabled { opacity: 0.4; cursor: not-allowed; background: rgba(0,0,0,0.3); }
        select:focus, textarea:focus { border-color: var(--orange); background: rgba(255,107,61,0.05); }

        .submit-btn { width: 100%; padding: 15px; background: var(--orange); border: none; border-radius: 12px; color: white; font-family: 'Orbitron'; font-weight: bold; cursor: pointer; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; }
        .submit-btn:hover { background: #e55a2d; box-shadow: 0 0 20px rgba(255, 107, 61, 0.4); transform: translateY(-2px); }
        
        .lock-notice { font-size: 10px; color: #ffbc00; margin-top: -20px; margin-bottom: 15px; display: block; font-style: italic; }
    </style>
</head>
<body>
    <div class="action-card">
        <h2>DISPATCH CONTROL</h2>
        <form method="post">
            <label>Update Tactical Status</label>
            <select name="status" required>
                <option value="" disabled selected>Select Status...</option>
                
                <option value="Assigned" 
                    <?php echo (in_array($currentStatus, ['Assigned', 'Team on the Way', 'Fire Relief in Progress', 'Request Completed'])) ? 'disabled' : ''; ?>>
                    Assign Team
                </option>

                <option value="Team on the Way" 
                    <?php echo (in_array($currentStatus, ['Team on the Way', 'Fire Relief in Progress', 'Request Completed'])) ? 'disabled' : ''; ?>>
                    En Route
                </option>

                <option value="Fire Relief in Progress" 
                    <?php echo (in_array($currentStatus, ['Fire Relief in Progress', 'Request Completed'])) ? 'disabled' : ''; ?>>
                    Active Response
                </option>

                <option value="Request Completed">Mission Accomplished</option>
            </select>

            <label>Assign to Response Team</label>
            <?php if($existingTeam > 0): ?><br/>
                <span class="lock-notice"><i class="fas fa-lock"></i> Deployment locked to current unit</span>
            <?php endif; ?>
            
            <select name="assign_to" <?php echo ($existingTeam > 0) ? 'disabled' : 'required'; ?>>
                <option value="" disabled <?php echo ($existingTeam == 0) ? 'selected' : ''; ?>>Select Team...</option>
                <?php 
                $ret = mysqli_query($con, "SELECT id, teamName FROM tblteams");
                while($row = mysqli_fetch_array($ret)) {
                    $selected = ($existingTeam == $row['id']) ? 'selected' : '';
                    echo "<option value='".$row['id']."' $selected>".$row['teamName']."</option>";
                }
                ?>
            </select>

            <label>Command Remarks (Optional)</label>
            <textarea name="remark" rows="4" placeholder="Enter tactical notes..."></textarea>

            <button type="submit" name="submit" class="submit-btn">EXECUTE UPDATE</button>
            <a href="request-details.php?requestid=<?php echo $rid; ?>" style="display:block; text-align:center; margin-top:20px; color:#64748b; text-decoration:none; font-size:12px;">Cancel Mission Update</a>
        </form>
    </div>
</body>
</html>
<?php } ?>