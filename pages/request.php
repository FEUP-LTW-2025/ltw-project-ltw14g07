<?php 
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');


    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/comment.class.php');
    require_once(__DIR__ . '/../database/user.class.php');

    require_once(__DIR__ . '/../utils/session.php');


    $session = new Session();

    if (!$session->isLoggedIn()) header('Location: ../pages/index.php');

    $db = getDatabaseConnection();
    $request = Request::getRequestByID($db, $_GET['id']);

    if ($request == NULL) {
        $session->addMessage('warning', 'Request does not exist');
        header('Location: ../pages/index.php');
        return;
    }

    if ($session->getUserID() !== $request->userID && 
        $session->getUserID() !== Service::getCreatorByID($db, $request->serviceID)) {
        $session->addMessage('warning', 'You do not have permission to access to this content');
        header('Location: ../pages/index.php');
        return;
    }

    $comments = Comment::getCommentsByRequestID($db, $_GET['id']);

    draw_header('request', $session);
    draw_request_page($request, $comments, $session);
    draw_footer();
?>