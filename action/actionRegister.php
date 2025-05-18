<?php
declare(strict_types=1);
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

try {
    $db = getDatabaseConnection();
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm = $_POST["confirm"];

        // Validate input
        if ($password !== $confirm) {
            $session->addMessage('error', 'Passwords dont match');
            header("Location: ../pages/signup.php?q=r");
            return;
        }

        if (User::register($db, $name, $email, $password)) {
            $session->addMessage('successs', 'Registration done successefully');
            header("Location: ../pages/index.php");
            exit;
        } else {
            $session->addMessage('error', 'Registration failed (email might already exist)');
            header("Location: ../pages/signup.php?q=r");
        }
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    header("Location: ../pages/index.php");
}

//    header('Location: ../pages/request.php?id=' . $_POST['requestID']);

?>

