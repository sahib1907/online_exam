<?php

if (!isset($_POST['uni'])) {
    echo "Group id not found";
    exit;
}

include ('../classes/autoload.php');

$uni_id = $_POST['uni'];

$result = $crud->get_data_for_select('qruplar','ad', '0', 'AND `uni_id`='.$uni_id);

if($result) {
    echo $result;
}
else {
    echo "Error occured!";
}