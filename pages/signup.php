<?php

require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../template/signup.tpl.php');

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();

$method = $_GET['q'];

draw_header('signup', $session);

if ($method === "r") {
    draw_register();
} else {
    draw_login();
}

?>