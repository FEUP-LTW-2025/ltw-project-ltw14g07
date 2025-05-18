<?php

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/comment.class.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if ($session->getCsrf() !== $_POST['csrf']) {
        die("Request is not legitimate");
    }

    if (trim($_POST['message']) === '') {
        $session->addMessage('warning', 'Message is empty');
        header('Location: ../pages/request.php?id=' . $_POST['requestID']);
        return;
    }

    if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['message'])) {
        die("Forbidden characters were used in the comment");
    }

    if (!$session->isLoggedIn()) header('Location: ../pages/index.php');


    $db = getDatabaseConnection();


    $comment = new Comment(
        null, 
        $_POST['requestID'],
        $session->getUserID(),
        trim($_POST['message']),
        null,
        null,
    );

    $comment->insertIntoDatabase($db);

    header('Location: ../pages/request.php?id=' . $_POST['requestID']);

?>