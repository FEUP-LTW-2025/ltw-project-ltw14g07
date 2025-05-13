<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/service.class.php');

$db = getDatabaseConnection();

$serviceID = $_POST['serviceID'];
Service::delete($db, $serviceID);

$path = __DIR__ . "/../images/service/$serviceID.jpg";
if (file_exists($path)) {
    unlink($path);
}

header('Location: ../pages');

?>