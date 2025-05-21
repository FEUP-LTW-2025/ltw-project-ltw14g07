<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/profile.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');


    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: signup.php');  
        exit();
    }

    $db = getDatabaseConnection();

    $userID = !empty($_GET['id']) ? (int) $_GET['id']  : $session->getUserID();
    $user = User::getUserByID($db, $userID);

    $pendingRequests = Request::getRequestByUserID($db, $userID, 'pending');
    $acceptedRequests = Request::getRequestByUserID($db, $userID, 'accepted');
    $doneRequests= Request::getRequestByUserID($db, $userID, 'done');

    $services = Service::getAllServicesByUserID($db, $userID);

    draw_header('profile', $session);
    draw_profile_resume($user);

    if ($session->getUserID() != $userID) {
        draw_service_cards($services);
    } else {
        draw_edit_profile();
        draw_request_cards($pendingRequests, 'Pending');
        draw_request_cards($acceptedRequests, 'Accepted');
        draw_request_cards($doneRequests, 'Done');
    }

    draw_footer();
?>