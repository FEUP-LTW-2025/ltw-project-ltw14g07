<?php 

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/manageServices.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../utils/session.php');


    $db = getDatabaseConnection();
    $session = new Session();

    $services = Service::getAllServicesByUserID($db, $session->getUserID());

    draw_header('manageServices', $session);
    draw_manageServices_page($services, $session->getUserID());  
    draw_footer();
?>