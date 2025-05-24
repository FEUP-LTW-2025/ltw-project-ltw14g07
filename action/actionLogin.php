<?php
declare(strict_types=1);
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

// Modified checkFailedAttempts function that works with your schema
function checkFailedAttempts(PDO $db, string $email): int {
    // First check if the columns exist
    $columnsExist = $db->query("PRAGMA table_info(Users)")->fetchAll();
    $hasFailedAttempts = false;
    $hasLastFailed = false;
    
    foreach ($columnsExist as $column) {
        if ($column['name'] === 'failed_attempts') $hasFailedAttempts = true;
        if ($column['name'] === 'last_failed_login') $hasLastFailed = true;
    }
    
    // If columns don't exist, return 0 (no failed attempts)
    if (!$hasFailedAttempts || !$hasLastFailed) {
        return 0;
    }
    
    $stmt = $db->prepare("SELECT failed_attempts, last_failed_login FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();
    
    if ($result && isset($result['last_failed_login'])) {
        $lastAttempt = new DateTime($result['last_failed_login']);
        $now = new DateTime();
        $interval = $now->diff($lastAttempt);
        
        // Reset counter if last attempt was more than 15 minutes ago
        if ($interval->i > 15) {
            $stmt = $db->prepare("UPDATE Users SET failed_attempts = 0 WHERE email = ?");
            $stmt->execute([$email]);
            return 0;
        }
        return (int)$result['failed_attempts'];
    }
    return 0;
}

try {
    $db = getDatabaseConnection();
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check for brute force attempts before authentication
    if (($failedAttempts = checkFailedAttempts($db, $email)) > 5) {
        header("Location: /pages/signup.php?q=l&error=locked");
        exit();
    }

    $user = User::login($db, $email, $password);

    if ($user) {
        // Reset failed attempts on successful login
        try {
            $stmt = $db->prepare("UPDATE Users SET failed_attempts = 0 WHERE email = ?");
            $stmt->execute([$email]);
        } catch (PDOException $e) {
            // Silently fail if columns don't exist
        }
        
        // Set session variables
        $_SESSION['userID'] = (int)$user->userID;
        $_SESSION['username'] = $user->name;
        $_SESSION['userRole'] = $user->role;
        $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['last_activity'] = time();

        // Admin-specific security measures
        if ($user->isAdmin()) {
            // Log admin login
            User::logSystemAction(
                $db, 
                $user->userID, 
                "Admin login", 
                "IP: " . $_SERVER['REMOTE_ADDR']
            );
            
            // Set shorter session timeout for admin (15 minutes)
            $_SESSION['admin_timeout'] = time() + 900;
            
            // Redirect to admin panel
            header("Location: /pages/admin.php");
        } else {
            // Regular user redirect
            header("Location: /pages/profile.php");
        }
        exit();
    } else {
        // Increment failed attempts counter
        try {
            $stmt = $db->prepare("UPDATE Users SET 
                failed_attempts = COALESCE(failed_attempts, 0) + 1,
                last_failed_login = CURRENT_TIMESTAMP
                WHERE email = ?");
            $stmt->execute([$email]);
        } catch (PDOException $e) {
            // Silently fail if columns don't exist
        }
        
        header("Location: /pages/signup.php?q=l&error=1");
        exit();
    }
}