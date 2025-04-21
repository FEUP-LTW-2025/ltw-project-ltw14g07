<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    $db = getDatabaseConnection();
    $service = Service::getService($db, $_GET['id']);
    print_r($service);

    draw_header('selectService');
    draw_service($service);
    draw_request_form();
    draw_footer();
?>