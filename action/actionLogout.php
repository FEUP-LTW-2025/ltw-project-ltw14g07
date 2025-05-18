<?php
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();
    $session->addMessage('success', 'Logged out successefully');
    $session->logout();

    header("Location: ../pages/index.php");
?>