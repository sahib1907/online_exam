<?php

include 'blocks/header.php';

//page
if (!isset($_GET['page']) || empty($_GET['page']) || $_GET['page'] < 0)  {
    $page = 1;
}
else {
    $page = $main->get_security($_GET['page']);
    if (!ctype_digit ($page)) {
        $page = 1;
    }
}

$last = 50;
$first = ($page-1)*$last;

$result = $main->show_list_for_ratings(''," LIMIT {$first},{$last}");
//pagination
$pag = $main->get_pagination('ratings');

?>
<style>
    th {
        text-align: center;
    }
</style>

<div class="panel panel-default tab" style="overflow-x: scroll;">
    <div class="pagination">
        <?=$pag;?>
    </div>

    <div class="panel-heading" style="text-transform: capitalize">
        <spam style="float: right;">Rating list</spam><a href="/index.php" class="btn btn-primary" style="">Ana səhifəyə keç...</a>
    </div>

<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 28.02.2017
 * Time: 19:03
 */

$table = <<<HTML
		<table 	class="table table-bordered table-hover">
HTML;

//$result = $main->show_list_for_ratings(" ORDER BY t1.rating DESC, t1.duz DESC, t1.sehv ASC, t1.vaxt ASC, t1.id DESC");

$table .= <<<HTML
        <tr>
HTML;
unset($result[0]['password']);
$table .= <<<HTML
				<th>No</th>
HTML;
foreach ($result[0] as $key => $value) {
    if ($key == 'rating') {
        $key = "rating (%)";
    }
    $table .= <<<HTML
				<th>{$key}</th>
HTML;
}

for ($i = 0; $i < count($result); $i++) {
    unset($result[$i]['password']);
    if ($result[$i]['username'] == $_SESSION['username']) {
        $table .= <<<HTML
        <tr class="active">
HTML;
    } else {
        $table .= <<<HTML
        <tr>
HTML;
    }
    $no = $i + 1;
    $table .= <<<HTML
				<td>{$no}</td>
HTML;
    foreach ($result[$i] as $key => $value) {
        $table .= <<<HTML
				<td>{$value}</td>
HTML;
    }
    $table .= <<<HTML
            </tr>
HTML;
}

$table .= <<<HTML
		</table>
HTML;

echo $table;

?>

</div>

<?php
include 'blocks/footer.php';
?>





