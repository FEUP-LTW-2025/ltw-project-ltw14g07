<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');

    $db = getDatabaseConnection();

    $userID = $_POST['userID'];
    $serviceID = $_POST['serviceID'];
    $title = $_POST['title'];
    $notes = $_POST['description'];

    $creationDate = '2025-04-20';


    $request = new Request(null, $serviceID, $userID, $title,
                        $notes, $creationDate, null, 'pending');

    $request->insertIntoDatabase($db);

    header('Location: ../pages');
?>