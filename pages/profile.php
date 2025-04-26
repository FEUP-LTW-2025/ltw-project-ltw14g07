<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/profile.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');

    //session_start();



    draw_header('profile');
    draw_profile_resume();
    
    draw_footer();
?>