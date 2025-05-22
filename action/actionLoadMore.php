<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/service.class.php');
require_once(__DIR__ . '/../template/service.tpl.php');

$offset = $_GET['offset'];
$userID = $_GET['userID'];
$limit = 6;

$db = getDatabaseConnection();

if (!empty($userID)) {
    $services = Service::getAllServicesByUserID($db, $userID, $offset);
} else {
    $services = Service::getAllServices($db, $limit, $offset);
}



foreach ($services as $service) {
    draw_service_card($service);
}


?>