<?php

declare(strict_types=1);
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Checkpoint A<br>";

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');


try {
    $db = getDatabaseConnection();
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Checkpoint B: POST request received<br>";
    $email = $_POST["email"];
    $password = $_POST["password"];
    echo "Email: $email<br>";

    $user = User::login($db, $email, $password);

    if ($user) {
        echo "Checkpoint C: User found<br>";
        $_SESSION["userID"] = $user->userID;
        $_SESSION["username"] = $user->name;
        header("Location: ../pages/profile.php");
        
    } else {
        echo "Invalid credentials.";
    }
}
?>
