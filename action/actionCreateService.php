<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    session_start();
    if (!isset($_SESSION['userID'])) header('Location: ../pages/signup.php');


    // CREATE/EDIT DATABASE SERVICE

    $db = getDatabaseConnection();

    $serviceID = ((int)$_POST['serviceID']) ?? null;
    $userID = $_SESSION['userID'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $languages = $_POST['languages'];
    $fields = $_POST['fields'];
    $hourlyRate = $_POST["hourlyRate"];
    $deliveryTime = $_POST["deliveryTime"];

    $creationDate = '2025-04-20';

    if (!empty($serviceID) && $_SESSION['userID'] !== Service::getCreatorByID($db, $serviceID))  {
        die("Forbidden access to update content");
    }

    $service = new Service($serviceID, $userID, null, $title,
                        $description, $hourlyRate, $deliveryTime,
                        $creationDate, $languages, $fields);

    
    $serviceID = $service->save($db);



    // CREATE/EDIT SERVICE IMAGE

    $basePath = __DIR__ . '/../images';
    if (!is_dir($basePath)) mkdir($basePath);
    if (!is_dir($basePath . "/service")) mkdir($basePath . "/service");

    $hasUploaded = isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']);
    if (file_exists("$basePath/service/$serviceID.jpg")) {
        if (!$hasUploaded) {
            header('Location: ../pages/service.php?serviceID=' . $serviceID);
            return;
        }
        unlink("$basePath/service/$serviceID.jpg");
    }

    $tempFileName = $_FILES['image']['tmp_name'];

    $image = @imagecreatefromjpeg($tempFileName);
    if (!$image) $image = @imagecreatefrompng($tempFileName);
    if (!$image) $image = @imagecreatefromgif($tempFileName);
    if (!$image) die('Unknown image format!');

    $imageFileName = "$basePath/service/$serviceID.jpg";  

    imagejpeg($image, $imageFileName);


    header('Location: ../pages/service.php?serviceID=' . $serviceID);
?>