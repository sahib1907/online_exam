<?php
/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 03.01.2018
 * Time: 01:22
 */
session_start();
ob_start();

error_reporting(0);

require 'classes/autoload.php';

$check_user = $main->check_user();
if (!$check_user) {
    header("Location: login.php");
}

$main->send_ip($_SESSION['client_id']);
?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111897026-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-111897026-1');
</script>