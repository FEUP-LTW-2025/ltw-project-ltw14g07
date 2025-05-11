<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/filters.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/filters.class.php');



    $db = getDatabaseConnection();
    $services = Service::getAllServices($db, 8);
    $filters = FIlters::getAllFilters($db);

    draw_header('mainPage');
    draw_vert_filters($filters);
    draw_service_cards($services);
    draw_footer();
?>