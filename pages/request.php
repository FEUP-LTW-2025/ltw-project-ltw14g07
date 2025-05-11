<?php 
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');


    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/user.class.php');



    $db = getDatabaseConnection();

    $request = Request::getRequestByID($db, $_GET['id']);
    $comments = Comment::getCommentsByRequestID($db, $_GET['id']);

    $userID = 1;  //temp
    $user = User::getUserByID($db, $userID);

    draw_header('request');
    draw_request_page($request, $comments, $user);
    draw_footer();
?>