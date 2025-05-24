<?php
declare(strict_types=1);
session_start([
    'cookie_secure' => true,
    'cookie_httponly' => true,
    'use_strict_mode' => true
]);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

try {
    $db = getDatabaseConnection();
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];

        // Validate input
        if ($password !== $confirm) {
            header("Location: ../pages/signup.php?q=r&error=password_mismatch");
            exit();
        }

        // ADMIN CREATION - WSL COMPATIBLE
        $role = 'client'; // Default role
        if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' && 
           isset($_POST['admin_secret']) && 
           str_starts_with($_POST['admin_secret'], 'wsl_admin_')) {
           $role = 'admin';
           error_log("WSL: Admin account created for " . $email);
        }

        // Registration with auto-login
        if (User::register($db, $name, $email, $password, $role)) {
            // Auto-login the new user
            $user = User::login($db, $email, $password);
            
            if ($user) {
                // Set session variables
                $_SESSION['userID'] = (int)$user->userID;
                $_SESSION['username'] = $user->name;
                $_SESSION['userRole'] = $user->role; // Preserves existing role logic
                $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
                
                // Redirect based on role
                if ($user->isAdmin()) {
                    header("Location: ../pages/admin.php");
                } else {
                    header("Location: ../pages/profile.php");
                }
                exit();
            }
        }
        
        // If registration or login failed
        header("Location: ../pages/signup.php?q=r&error=registration_failed");
        exit();
    }
} catch (PDOException $e) {
    header("Location: ../pages/signup.php?q=r&error=database_error");
    exit();
}



