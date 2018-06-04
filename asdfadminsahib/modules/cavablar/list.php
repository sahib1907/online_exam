<?php
    if (isset($_GET['p'])) {
        $fenn = $_GET['p'];
    }
    else {
        $fenn = 0;
    }

    $arr = $crud->list_for_cavablar($_GET['module'], 'fennler', "WHERE c1.deleted=0 AND c1.fenn_id={$fenn}");

    $fennler = $crud->show_list('fennler');
    $options = '';
    $selected = '';
    for ($i=0; $i<count($fennler); $i++) {
        if ($fennler[$i]['id'] == $fenn) {
            $selected = 'selected';
        }
        else {
            $selected = '';
        }
        $options .= <<<HTML
            <option {$selected} value="{$fennler[$i]['id']}">{$fennler[$i]['ad']}</option>
HTML;
    }
?>

<h1 class="page-header">Tables</h1>
<div class="panel panel-default">
    <div class="panel-heading" style="text-transform: capitalize;">
        <?=$_GET['module'];?> Tables <spam style="float: right;"><a href="index.php?module=<?=$_GET['module'];?>&action=add">Add <?=$_GET['module'];?></a></spam>
    </div>
    <div class="panel-body">
        <div class="table-responsive" style="Overflow-x: scroll;">
            <input type="text" id="myInput" onkeyup="tableSearch()" placeholder="Search for names.." class="form-control" style="margin-bottom: 5px;">
            <select class="form-control" name="fenn_id" id="selectFenn" onchange="changeValue()" module="<?=$_GET['module'];?>">
                <option value="0">Select option</option>
                <?=$options;?>
            </select>
            <br>
            <table class="table table-striped table-bordered table-hover" id="myTable">
                <thead>
                <tr>
                    <?php
                    foreach ($arr[0] as $key=>$value) {
                        if ($key == 'sual_id') {
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
                            if ($key == 'sual_id') {
                                continue;
                            }
                            if ($key == 'duzgun') {
                                if ($value == '1') {
                                    $value = 'Yes';
                                }
                                else {
                                    $value = 'No';
                                }
                            }
                            if ($key == 'sual') {
                                $value = <<<HTML
                                    <a href="?module=suallar&action=edit&id={$arr[$i]['sual_id']}">{$value}</a>
HTML;
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

