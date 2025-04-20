<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/createService.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/filters.class.php');

    $db = getDatabaseConnection();
    $filters = Filters::getAllFilters($db);

    $userID = 1;  //temp until login is done

    draw_header('createService');
    draw_form($filters, $userID);
    draw_footer();
?>