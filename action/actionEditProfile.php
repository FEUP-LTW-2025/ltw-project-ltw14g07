<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    header('Location: ../pages/signup.php');
    exit();
}

$db = getDatabaseConnection();
$userID = $_SESSION['userID'];

// Get the submitted form data
$name = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;

// Validate the input
if ($name === null || $description === null) {
    // Redirect back to the profile editor with an error message
    header('Location: ../pages/profile_editor.php?error=missing_fields');
    exit();
}

// Update the user's profile in the database
User::updateUserProfile($db, $userID, $name, $description);

// Redirect back to the profile page with a success message
header('Location: ../pages/profile.php?success=profile_updated');
exit();
?>