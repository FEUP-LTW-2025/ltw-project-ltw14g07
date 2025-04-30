<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');


    $db = getDatabaseConnection();

    $service = Service::getService($db, $_GET['serviceID']);
    $request = null;
    
    if (isset($_GET['requestID'])) {
        $request = Request::getRequestByID($db, $_GET['requestID']);
    }    
    
    $requests = Request::getRequestByServiceID($db, $_GET['serviceID']);

    draw_header('selectService');

    //make conditional if user is who created page, makes less bloated
    draw_service_page($service, $requests, $request);
    draw_footer();
?>