<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');

    session_start();
    if (!isset($_SESSION['userID'])) header('Location: ../pages/signup.php');

    $db = getDatabaseConnection();

    //updating request
    if (!empty($_POST['requestID'])) {
        $r = Request::getRequestByID($db, $_POST['requestID']);
        $r->title =  (!empty($_POST['title'])) ? $_POST['title'] : $r->title;
        $r->description =  (!empty($_POST['description'])) ? $_POST['description'] : $r->description;
        $r->status = (!empty($_POST['decision'])) ? $_POST['decision'] : $r->status;
        $r->save($db);

        header('Location: ../pages/request.php?id=' . $r->requestID);
        return;
    } 

    //creating request
    $userID = $_SESSION['userID'];
    $serviceID = $_POST['serviceID'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $creationDate = '2025-04-20';

    $request = new Request(null, $serviceID, $userID, $title,
                        $description, $creationDate, null, 'pending');
    
    $request->save($db);   

    header('Location: ../pages');
?>