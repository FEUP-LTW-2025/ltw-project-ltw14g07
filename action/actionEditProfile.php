<?php
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

// Check if the user is logged in
if (!$session->isLoggedIn()) {
    header('Location: ../pages/signup.php');
    exit();
}

if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['title']) ||
    !preg_match ("/^[a-zA-Z\s]+$/", $_POST['description'])) {
    die("Forbidden characters were used");
}

$db = getDatabaseConnection();
$userID = $session->getUserID();

// Get the submitted form data
$name = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$password = $_POST['password'] ?? null;

// Validate the input
if ($name === null || $description === null) {
    // Redirect back to the profile editor with an error message
    header('Location: ../pages/profile_editor.php?error=missing_fields');
    exit();
}

$user = User::getUserByID($db, $userID);
$user->name = $name;
$user->description = $description;
if($password !== null && $password !== '') {
    // Only update the password if it is provided
    $user->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password before storing it
} else {
    // If no new password is provided, keep the old one
    $user->password = $user->password;
} // Hash the password before storing it
//role in the future
//$user->role = $role;


// Update the user's profile in the database
$user->updateDatabase($db);

// Redirect back to the profile page with a success message
header('Location: ../pages/profile.php?success=profile_updated');
exit();
?>