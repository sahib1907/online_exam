<?php

if (!isset($_POST['allid'])) {
    echo "allid not found";
    exit;
}

include ('../classes/autoload.php');

$allid = $_POST['allid'];
$table = $_POST['module'];
$type = $_POST['type'];

$result = $crud->logicalDeleteMany($allid, $table, $type);

if($result) {
    echo "Deleted successfully";
}
else {
    echo "Error occured!";
}