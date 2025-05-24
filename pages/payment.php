<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/payment.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/service.class.php');
    require_once(__DIR__ . '/../database/request.class.php');
    require_once(__DIR__ . '/../database/payment.class.php');

    require_once(__DIR__ . '/../utils/session.php');


    $session = new Session();
    if (!$session->isLoggedIn()) {
        header('Location: /pages/signup.php');
    }

    $db = getDatabaseConnection();

    $request = Request::getRequestByID($db, $_GET['requestID']);
    $service = Service::getService($db, $_GET['serviceID']);
    if ($request) {
        $payment = Payment::getPaymentByRequestID($db, $request->requestID);
    }

    if ($service === null || $request === null) {
        $session->addMessage('warning', "Service or Request Does not exist");
        header('Location: ../pages/index.php');
        return;
    }

    if ($request->userID !== $session->getUserID()) {
        $session->addMessage('warning', "You have no permission to access this (payment)");
        header('Location: ../pages/index.php');
        return;
    }

    if (!empty($payment)) {
        $session->addMessage('warning', "Credentials were already inserted for this request");
        header('Location: ../pages/index.php');
        return;
    }

    draw_header('payment', $session);
    draw_payment_form($request->requestID, $service->serviceID, $session);
    draw_footer();
    
?>