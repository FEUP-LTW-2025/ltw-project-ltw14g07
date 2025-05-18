<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/service.class.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
$db = getDatabaseConnection();

$serviceID = $_POST['serviceID'];
Service::delete($db, $serviceID);

$path = __DIR__ . "/../images/service/$serviceID.jpg";
if (file_exists($path)) {
    unlink($path);
}

$session->addMessage('success', 'Service deleted successfully');

header('Location: ../pages');

?>