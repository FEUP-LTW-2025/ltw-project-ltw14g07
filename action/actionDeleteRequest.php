<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/request.class.php');

$db = getDatabaseConnection();

$requestID = $_POST['requestID'];
Request::delete($db, $requestID);

header('Location: ../pages');

?>