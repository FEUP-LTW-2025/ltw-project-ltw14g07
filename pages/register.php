<?php
declare(strict_types=1);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
            die("Passwords don't match");
        }

        if (User::register($db, $name, $email, $password)) {
            // Registration successful - redirect to login
            header("Location: /html/createService.html");
            exit;
        } else {
            echo "Registration failed (email may already exist)";
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>