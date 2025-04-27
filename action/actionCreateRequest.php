<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');

    $db = getDatabaseConnection();

    $request = null;
    $requestID = null;
    $status = 'pending';

    if (!empty($_POST['requestID'])) {
        $r = Request::getRequestByID($db, $_POST['requestID']);
        $requestID = $r->requestID;
        $status = $r->status;
    } 

    $userID = 1;  //temp until session start
    //$userID = $_POST['userID'];
    $serviceID = $_POST['serviceID'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $creationDate = '2025-04-20';

    $request = new Request($requestID, $serviceID, $userID, $title,
                        $description, $creationDate, null, $status);
    

    $request->save($db);   

    header('Location: ../pages');
?>