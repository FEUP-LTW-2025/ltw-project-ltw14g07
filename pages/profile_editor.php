<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/profile.tpl.php');
    require_once(__DIR__ . '/../template/service.tpl.php');
    require_once(__DIR__ . '/../template/request.tpl.php');
    require_once(__DIR__ . '/../template/profile_editor.tpl.php');


    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/user.class.php');


    session_start();

    if (!isset($_SESSION['userID'])) {
        header('Location: signup.php');  
        exit();
    }

    $db = getDatabaseConnection();
    
    $userID = $_SESSION['userID'];
    $user = User::getUserByID($db, $userID);
    


    draw_header('profile');
    draw_profile_editor($user);
    draw_footer();
?>