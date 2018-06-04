<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 31.05.2017
 * Time: 14:41
 */

if (!isset($_POST['fennid'])){
    echo "Hacking attempt!";
    exit;
}

$fenn_id = $_POST['fennid'];
$module = $_POST['module'];

$link = "index.php?module=".$module."&action=list&p=".$fenn_id;

echo $link;