<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');



    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    $db = getDatabaseConnection();
    $service = Service::getService($db, $_GET['id']);

    $userID = 1;    //temp

    draw_header('selectService');
    draw_service($service);
    draw_request_form($service->userName, $userID, $_GET['id']);
    draw_footer();
?>