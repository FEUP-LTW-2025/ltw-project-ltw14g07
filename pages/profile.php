<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/profile.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    //session_start();

    $db = getDatabaseConnection();
    //$user = User::getUserByID();

    $services = Service::getAllServices($db, 8);

    draw_header('profile');
    draw_profile_resume();
    draw_service_cards($services);
    
    draw_footer();
?>