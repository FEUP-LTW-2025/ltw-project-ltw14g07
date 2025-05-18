<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/createService.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/filters.class.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $db = getDatabaseConnection();
    $filters = Filters::getAllFilters($db);

    $service = null;
    if (!empty($_GET['serviceID'])) {
        $service = Service::getService($db, $_GET['serviceID']);

        if ($session->getUserID() !== $service->userID) {
            $session->addMessage('warning', 'You cant edit this content');
            header('Location: ../pages/index.php');
            return;
        }
    }

    draw_header('createService', $session);
    draw_createService_page($service, $filters, $session);
    draw_footer();
?>