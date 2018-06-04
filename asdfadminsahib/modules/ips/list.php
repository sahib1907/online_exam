<?php
if (!isset($_GET['page']) || empty($_GET['page']) || $_GET['page'] < 0)  {
    $page = 1;
}
else {
    $page = $crud->get_security($_GET['page']);
    if (!ctype_digit ($page)) {
        $page = 1;
    }
}

$last = 50;
$first = ($page-1)*$last;

$arr = $crud->list_for_categories($_GET['module'], 'clients', "t1.id, t1.ip, CONCAT(t2.name, ' ', t2.surname) as `user`, t1.location, t1.date", 't1.user_id=t2.id', "ORDER BY `id` DESC LIMIT {$first},{$last}");

//pagination
$pag = $crud->get_pagination($_GET['module']);
?>

<h1 class="page-header">Tables</h1>
<div class="pagination">
    <?=$pag;?>
</div>
<div class="panel panel-default">
    <div class="panel-heading" style="text-transform: capitalize;">
        <?=$_GET['module'];?> Tables
    </div>
    <div class="panel-body">
        <div class="table-responsive" style="Overflow-x: scroll;">
            <input type="text" id="myInput" onkeyup="tableSearch()" placeholder="Search for names.." class="form-control" style="margin-bottom: 5px;">
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                <tr>
                    <?php
                    foreach ($arr[0] as $key=>$value) {
                        if ($key == 'deleted') {
                            continue;
                        }
                        echo "<th>".$key."</th>";
                    }
                    ?>
                    <th colspan="2">Manage</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i=0;$i<count($arr);$i++) {
                        echo '<tr class="odd gradeX">';
                        foreach ($arr[$i] as $key=>$value) {
                            if ($key == 'deleted') {
                                continue;
                            }
                            echo "<td>".$value."</td>";
                        }
                        $td = <<<HTML
                            <td><a class="delete" href="index.php?module={$_GET['module']}&action=delete&id={$arr[$i]['id']}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            <td><input type="checkbox" class="rowDelete" row_id="{$arr[$i]['id']}"></td>
                            </tr>
HTML;
                        echo $td;
                    }
                    ?>
                </tbody>
            </table>
            <button class="btn btn-warning deleteRows" style="outline: none; float: right;" module="<?= $_GET['module'];?>">Delete selected</button>
        </div>
    </div>
</div>

