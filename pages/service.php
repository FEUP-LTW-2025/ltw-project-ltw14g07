<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');

    require_once(__DIR__ . '/../utils/session.php');


    $session = new Session();

    $db = getDatabaseConnection();

    $service = Service::getService($db, $_GET['serviceID']);
    $request = null;

    if ($service == NULL) {
        $session->addMessage('warning', 'Service does not exist');
        header('Location: ../pages/index.php');
        return;
    }
    
    if (isset($_GET['requestID'])) {
        $request = Request::getRequestByID($db, $_GET['requestID']);

        if ($session->getUserID() !== $request->userID) {
            $session->addMessage('warning', 'You do not have permission to access to this content');
            header('Location: ../pages/index.php');
            return;
        }
    }    
    
    $pendingRequests = Request::getRequestByServiceID($db, $_GET['serviceID'], 'pending');
    $acceptedRequests = Request::getRequestByServiceID($db, $_GET['serviceID'], 'accepted');


    draw_header('selectService', $session);
    draw_service_page($service, $pendingRequests, $acceptedRequests, $request, $session);
    draw_footer();
?>