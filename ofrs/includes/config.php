<?php
/**
 * OFRS Tactical Command - Global Configuration
 * Multi-Driver Support: PDO (Frontend) + MySQLi (Admin Legacy)
 */

// Start session only if one isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. DATABASE CONFIGURATION
define('DB_HOST', 'localhost');
define('DB_NAME', 'ofrsdb'); 
define('DB_USER', 'root');   
define('DB_PASS', '');       

date_default_timezone_set('Asia/Kolkata');

// 2. PDO CONNECTION (Modern Frontend & Maps)
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Critical DB Failure: ' . $e->getMessage());
    $pdo = null;
}

// 3. MYSQLI CONNECTION (Admin Panel Compatibility)
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (mysqli_connect_errno()) {
    die("Connection Fail: " . mysqli_connect_error());
}

/** * --- CORE HELPER FUNCTIONS --- 
 */

// Safety: Escape HTML to prevent XSS
function h($str) {
    return htmlspecialchars((string)($str ?? ''), ENT_QUOTES, 'UTF-8');
}

// Global Notification System
function setToast($type, $msg) {
    $_SESSION['toast'] = ['type' => $type, 'msg' => $msg];
}

// Tactical ID Generator: FIRE-123456
function generateRef() {
    return "FIRE-" . mt_rand(100000, 999999);
}

// Security: CSRF Management
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf($token) {
    return isset($_SESSION['csrf_token']) && !empty($token) && hash_equals($_SESSION['csrf_token'], $token);
}

/** * --- TACTICAL MULTIMEDIA UPLINK --- 
 * Handles multi-file uploads and DB sync
 */
function uploadPhotos($files, $reportId) {
    global $pdo;
    $uploadedNames = [];
    
    // Determine path based on where we are calling from (Admin vs Root)
    $prefix = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
    $uploadDir = $prefix . 'uploads/incidents/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Ensure tbl_photos exists
    if ($pdo) {
        $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_photos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            report_id INT NOT NULL,
            photo_name VARCHAR(255) NOT NULL,
            upload_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    foreach ($files['name'] as $key => $val) {
        if ($files['error'][$key] == 0) {
            $tmpName = $files['tmp_name'][$key];
            $ext = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
            
            // Unique Tactical Filename
            $newName = "RPT_" . $reportId . "_" . bin2hex(random_bytes(4)) . "." . $ext;
            $finalPath = $uploadDir . $newName;

            if (move_uploaded_file($tmpName, $finalPath)) {
                if ($pdo) {
                    $stmt = $pdo->prepare("INSERT INTO tbl_photos (report_id, photo_name) VALUES (?, ?)");
                    $stmt->execute([$reportId, $newName]);
                }
                $uploadedNames[] = $newName;
            }
        }
    }
    return $uploadedNames;
}

/** * --- ANALYTICS & VISUALIZATION --- 
 */

function getHomepageStats($pdo) {
    if (!$pdo) return ['total' => 0, 'avg_response' => 12, 'resolution_rate' => 0, 'teams' => 0];
    
    $stats = [];
    $stats['total'] = (int)$pdo->query('SELECT COUNT(*) FROM tblfirereport')->fetchColumn();
    $stats['teams'] = (int)$pdo->query('SELECT COUNT(*) FROM tblteams')->fetchColumn();
    $stats['avg_response'] = 12; 

    $completed = (int)$pdo->query("SELECT COUNT(*) FROM tblfirereport WHERE status = 'Request Completed'")->fetchColumn();
    $stats['resolution_rate'] = $stats['total'] > 0 ? round(($completed / $stats['total']) * 100) : 0;
    
    return $stats;
}

function fetchMapReports($pdo, $limit = 20) {
    if (!$pdo) return [];
    try {
        $stmt = $pdo->prepare("SELECT id, fullName, location, status, latitude, longitude FROM tblfirereport WHERE latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) { return []; }
}

function formatStatusClass($status) {
    $status = strtolower(trim((string)$status));
    return match ($status) {
        'request completed' => 'success',
        'assigned', 'team on the way', 'fire relief in progress' => 'warning',
        default => 'info',
    };
}
?>