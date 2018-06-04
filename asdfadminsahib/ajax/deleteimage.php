<?php
/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 28.04.2017
 * Time: 10:30
 */

if (!isset($_POST['image']) || !isset($_POST['table']) || !isset($_POST['id']) ){
    echo "Hacking attempt!";
    exit;
}

$image  = $_POST['image'];
$table = $_POST['table'];
$id = $_POST['id'];

include ('../classes/autoload.php');

if ($crud->deleteImage($id, $table, $image)) {
    echo "Deleted";
}
else {
    echo "Error while image delete!";
}