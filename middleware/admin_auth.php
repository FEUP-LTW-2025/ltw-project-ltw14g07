<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

function ensureAdminAccess() {
    // Start secure session with enhanced security settings
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_secure' => true,    // Requires HTTPS
            'cookie_httponly' => true,  // Prevents JavaScript access
            'use_strict_mode' => true,  // Prevents session fixation
            'cookie_samesite' => 'Strict' // CSRF protection
        ]);
    }
    
    // Basic session validation
    if (!isset($_SESSION['userID'])) {
        header("Location: /pages/signup.php?q=l");
        exit();
    }
    
    $db = getDatabaseConnection();
    $user = User::getUserByID($db, $_SESSION['userID']);
    
    // Strict admin role verification
    if (!$user || $user->role !== 'admin') {
        User::logSystemAction(
            $db,
            $_SESSION['userID'] ?? 0,
            "Unauthorized admin access attempt",
            "IP: " . $_SERVER['REMOTE_ADDR']
        );
        header("Location: /pages/index.php");
        exit();
    }
    
    // Session integrity verification
    $sessionValid = isset($_SESSION['userAgent'], $_SESSION['ipAddress'])
        && $_SESSION['userAgent'] === $_SERVER['HTTP_USER_AGENT']
        && $_SESSION['ipAddress'] === $_SERVER['REMOTE_ADDR'];
    
    if (!$sessionValid) {
        session_destroy();
        header("Location: /pages/signup.php?q=l&error=session");
        exit();
    }
    
    // Session timeout management (15 minutes)
    $currentTime = time();
    if (isset($_SESSION['admin_timeout']) && $currentTime > $_SESSION['admin_timeout']) {
        User::logSystemAction(
            $db,
            $user->userID,
            "Admin session timeout",
            "Automatic logout after inactivity"
        );
        session_destroy();
        header("Location: /pages/signup.php?q=l&error=timeout");
        exit();
    }
    
    // Update session activity
    $_SESSION['last_activity'] = $currentTime;
    $_SESSION['admin_timeout'] = $currentTime + 900; // 15 minutes
    
    // Periodic session ID regeneration (every 30 minutes)
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = $currentTime;
    } elseif ($currentTime - $_SESSION['created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['created'] = $currentTime;
    }
}