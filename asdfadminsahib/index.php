<?php

session_start();
include('classes/autoload.php');

if (!$session->checkUser()) {
    header('Location: login.php');
}

if (isset($_GET['module']) && isset($_GET['action'])) {
    $path = $routing->get_path($_GET['module'], $_GET['action']);
}
else {
    $path = 'modules/default/index.php';
}

include('blocks/header.php');
include('blocks/middle.php');
include('blocks/footer.php');

?>