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


    //session_start();

    $db = getDatabaseConnection();

    $userID = 1; //temp

    $user = User::getUserByID($db, $userID);

    $pendingRequests = Request::getRequestByUserID($db, $userID, 'pending');
    $acceptedRequests = Request::getRequestByUserID($db, $userID, 'accepted');
    $doneRequests= Request::getRequestByUserID($db, $userID, 'done');


    draw_header('profile');
    draw_profile_resume($user);
    draw_request_cards($pendingRequests, 'Pending');
    draw_request_cards($acceptedRequests, 'Accepted');
    draw_request_cards($doneRequests, 'Done');
    draw_footer();
?>