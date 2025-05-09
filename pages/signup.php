<?php

require_once(__DIR__ . '/../template/common.tpl.php');
require_once(__DIR__ . '/../template/signup.tpl.php');


$method = $_GET['q'];

draw_header('signup');

if ($method === "r") {
    draw_register();
} else {
    draw_login();
}

?>