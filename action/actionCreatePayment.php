<?php

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/payment.class.php');
    require_once(__DIR__ . '/../database/service.class.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if ($session->getCsrf() !== $_POST['csrf']) {
        die("Request is not legitimate");
    }

    if (!$session->isLoggedIn()) header('Location: ../pages/index.php');

    $db = getDatabaseConnection();

    $serviceID = $_POST['serviceID'];
    $service = Service::getService($db, $serviceID);

    $requestID = $_POST['requestID'];
    $paymentMethod = $_POST['card_type'];
    $status = 'pending';
    $amount = $service->deliveryTime * ($service->hourlyRate * 4 * 7);   //4 hrs a day/week

    $payment = new Payment(null, $requestID, $amount,
     $status, $paymentMethod, null);

    $payment->save($db);

    header('Location: ../pages/request.php?id=' . $requestID);

?>


