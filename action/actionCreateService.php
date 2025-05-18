<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->getUserID()) header('Location: ../pages/signup.php');

    if ($session->getCsrf() !== $_POST['csrf']) {
        $session->addMessage('error', 'Request was forged');
        header('Location: ../pages/index.php');
        return;
    }
    

    // CREATE/EDIT DATABASE SERVICE

    $db = getDatabaseConnection();

    $serviceID = isset($_POST['serviceID']) ? (int)$_POST['serviceID'] : null;
    $userID = $session->getUserID();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $languages = $_POST['languages'];
    $fields = $_POST['fields'];
    $hourlyRate = $_POST["hourlyRate"];
    $deliveryTime = $_POST["deliveryTime"];


    $service = new Service($serviceID, $userID, null, $title,
                        $description, $hourlyRate, $deliveryTime,
                        null, $languages, $fields);

    
    $label = !empty($serviceID) ? "edited" : "created";
    $session->addMessage('success', 'Service ' . $label . " with success");

    $serviceID = $service->save($db);



    // CREATE/EDIT SERVICE IMAGE

    $basePath = __DIR__ . '/../images';
    if (!is_dir($basePath)) mkdir($basePath);
    if (!is_dir($basePath . "/service")) mkdir($basePath . "/service");

    $hasUploaded = isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name']);
    if (!$hasUploaded) {
        header('Location: ../pages/service.php?serviceID=' . $serviceID);
        return;
    }

    if (file_exists("$basePath/service/$serviceID.jpg")) {
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