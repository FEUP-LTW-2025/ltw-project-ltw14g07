<?php

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/comment.class.php');


    if (trim($_POST['message']) === '') {
        die(header('Location: ../pages/request.php?id=' . $_POST['requestID']));
    }

    session_start();

    if (!isset($_SESSION['userID'])) header('Location: ../pages/index.php');


    $db = getDatabaseConnection();

    $creationDate = '2025-04-20';

    $comment = new Comment(
        null, 
        $_POST['requestID'],
        $_SESSION['userID'],
        $_POST['message'],
        $creationDate,
        null,
    );

    $comment->insertIntoDatabase($db);

    header('Location: ../pages/request.php?id=' . $_POST['requestID']);

?>