<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');



    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');


    $db = getDatabaseConnection();
    $service = Service::getService($db, $_GET['id']);

    $userID = 1;    //temp

    $requests = Request::getRequestByServiceID($db, $_GET['id']);


    draw_header('selectService');
    draw_service_page($service, $userID, $requests);
    draw_footer();
?>