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

$exam_id = $_POST['fennid'];

include ('../classes/autoload.php');

$res = $main->getInterval($exam_id);

if (!$res) {
    echo "Error!";
}
else {
    echo $res;
}