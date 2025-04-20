<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    $db = getDatabaseConnection();

    $userID = $_POST['userID'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $languages = $_POST['languages'];
    $fields = $_POST['fields'];

    $hourlyRate = $_POST["hourlyRate"];
    $deliveryTime = $_POST["deliveryTime"];

    $creationDate = '2025-04-20';


    $service = new Service(null, $userID, null, $title,
                        $description, $hourlyRate, $deliveryTime,
                        $creationDate, $languages, $fields);

    $service->insertIntoDatabase($db);

    header('Location: ../pages');
?>