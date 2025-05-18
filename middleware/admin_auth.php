<?php
require_once(__DIR__ . '/../database/user.class.php');

function ensureAdminAccess() {
    session_start();
    global $db;
    
    if (!isset($_SESSION['userID'])) {
        header("Location: /pages/signup.php?q=l");
        exit();
    }
    
    $user = User::getUserByID($db, $_SESSION['userID']);
    if (!$user || !$user->isAdmin()) {
        header("Location: /pages/index.php");
        exit();
    }
}
?>