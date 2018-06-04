<?=$arr = $crud->list_for_categories($_GET['module'], 'qruplar', 't1.id, CONCAT(t1.name, " ", t1.surname) as `full_name`, t1.username, t1.email, t2.ad as `group`', 't1.qrup_id=t2.id', " ORDER By t1.id DESC");?>

<h1 class="page-header">Tables</h1>
<div class="panel panel-default">
    <div class="panel-heading" style="text-transform: capitalize;">
        <?=$_GET['module'];?> Tables <spam style="float: right;"><a href="index.php?module=<?=$_GET['module'];?>&action=add">Add <?=$_GET['module'];?></a></spam>
    </div>
    <div class="panel-body">
        <div class="table-responsive" style="Overflow-x: scroll;">
            <input type="text" id="myInput" onkeyup="tableSearch()" placeholder="Search for names.." class="form-control" style="margin-bottom: 5px;">
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                <tr>
                    <?php
                    foreach ($arr[0] as $key=>$value) {
                        if ($key == 'password') {
                            continue;
                        }
                        if ($key == 'deleted') {
                            continue;
                        }
                        echo "<th>".$key."</th>";
                    }
                    ?>
                    <th colspan="3">Manage</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i=0;$i<count($arr);$i++) {
                        echo '<tr class="odd gradeX">';
                        foreach ($arr[$i] as $key=>$value) {
                            if ($key == 'password') {
                                continue;
                            }
                            if ($key == 'deleted') {
                                continue;
                            }
                            echo "<td>".$value."</td>";
                        }
                        $td = <<<HTML
                            <td><a href="index.php?module={$_GET['module']}&action=edit&id={$arr[$i]['id']}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
                            <td><a class="delete" href="index.php?module={$_GET['module']}&action=delete&id={$arr[$i]['id']}"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            <td><input type="checkbox" class="rowDelete" row_id="{$arr[$i]['id']}"></td>
                            </tr>
HTML;
                        echo $td;
                    }
                    ?>
                </tbody>
            </table>
            <button class="btn btn-warning logicalDeleteRows" style="outline: none; float: right;" module="<?= $_GET['module'];?>">Delete selected</button>
        </div>
    </div>
</div>

