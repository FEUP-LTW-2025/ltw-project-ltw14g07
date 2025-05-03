<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    $db = getDatabaseConnection();

    if (!empty($_POST['serviceID'])) {
        $s = Service::getService($db, $_POST['serviceID']);

    }

    $serviceID = ((int)$_POST['serviceID']) ?? null;

    $userID = $_POST['userID'];   //TEMPPPPP
    $title = $_POST['title'];
    $description = $_POST['description'];

    $languages = $_POST['languages'];
    $fields = $_POST['fields'];

    $hourlyRate = $_POST["hourlyRate"];
    $deliveryTime = $_POST["deliveryTime"];

    $creationDate = '2025-04-20';


    $service = new Service($serviceID, $userID, null, $title,
                        $description, $hourlyRate, $deliveryTime,
                        $creationDate, $languages, $fields);

    $service->save($db);

    header('Location: ../pages');
?>