<?php

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/comment.class.php');


    if (trim($_POST['message']) === '') {
        die(header('Location: ../pages/request.php?id=' . $_POST['requestID']));
    }

    if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['message'])) {
        die("Forbidden characters were used in the comment");
    }

    session_start();

    if (!isset($_SESSION['userID'])) header('Location: ../pages/index.php');


    $db = getDatabaseConnection();


    $comment = new Comment(
        null, 
        $_POST['requestID'],
        $_SESSION['userID'],
        $_POST['message'],
        null,
        null,
    );

    $comment->insertIntoDatabase($db);

    header('Location: ../pages/request.php?id=' . $_POST['requestID']);

?>