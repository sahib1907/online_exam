<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 24.02.2017
 * Time: 20:23
 */
session_start();
$_SESSION['username'] = '';
$_SESSION['selectuser'] = '';
setcookie("username", "0", time() - (3600*24*7));
setcookie("user_pass", "0", time() - (3600*24*7));
header('Location: login.php');