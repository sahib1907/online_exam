<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 24.02.2017
 * Time: 20:23
 */
session_start();
unset($_SESSION['client_id']);
if (isset($_COOKIE['client_id'])) {
    setcookie("client_id", "0", time() - (3600*24*7));
    setcookie("client_pass", "0", time() - (3600*24*7));
}
header('Location: login.php');