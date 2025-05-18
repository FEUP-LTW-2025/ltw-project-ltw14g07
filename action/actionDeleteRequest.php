<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/request.class.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

$db = getDatabaseConnection();

$requestID = $_POST['requestID'];
Request::delete($db, $requestID);

$session->addMessage('success', 'Request deleted successfully');

header('Location: ../pages');

?>