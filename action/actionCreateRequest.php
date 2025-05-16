<?php 

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');

    session_start();
    if (!isset($_SESSION['userID'])) header('Location: ../pages/signup.php');

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        die("Request is not legitimate");
    }

    if (!empty($_POST['title']) && !empty($_POST['description'])) {
        if (!preg_match ("/^[a-zA-Z\s]+$/", $_POST['title']) ||
            !preg_match ("/^[a-zA-Z\s]+$/", $_POST['description'])) {
            die("Forbidden characters were used");
        }
    }

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

    print_r($serviceID);
    print_r($userID);

    print_r($_SESSION);


    $request = new Request(null, $serviceID, $userID, $title,
                        $description, null, null, 'pending');
    
    $request->save($db);   

    header('Location: ../pages');
?>