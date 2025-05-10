<?php 
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');


    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    session_start();

    if (!isset($_SESSION['userID'])) header('Location: ../pages/index.php');

    $db = getDatabaseConnection();
    $request = Request::getRequestByID($db, $_GET['id']);

    if ($request == NULL) {
        die("No such Request");
    }

    if ($_SESSION['userID'] !== $request->userID && 
    $_SESSION['userID'] !== Service::getCreatorByID($db, $request->serviceID)) {
        die("Insufficient permissions");
    }

    $comments = Comment::getCommentsByRequestID($db, $_GET['id']);

    draw_header('request');
    draw_request_page($request, $comments);
    draw_footer();
?>