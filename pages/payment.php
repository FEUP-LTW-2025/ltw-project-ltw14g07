<?php
    require_once(__DIR__ . '/../template/common.tpl.php');
    require_once(__DIR__ . '/../template/payment.tpl.php');

    require_once(__DIR__ . '/../database/connection.db.php');

    require_once(__DIR__ . '/../utils/session.php');


    $session = new Session();
    if (!$session->isLoggedIn()) {
        header('Location: /pages/signup.php');
    }

    $db = getDatabaseConnection();

    draw_header('payment', $session);
    draw_payment_form();
    draw_footer();

?>