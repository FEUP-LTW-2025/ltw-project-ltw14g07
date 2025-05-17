<?php 

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/manageServices.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../utils/session.php');


    $db = getDatabaseConnection();
    //$user = User::getUserByID();
    $userID = 1;  //temp 
    $session = new Session();

    $services = Service::getAllServicesByUserID($db, $userID);

    draw_header('manageServices', $session);
    draw_manageServices_page($services);  
    draw_footer();
?>