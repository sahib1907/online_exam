<?php
    if (!isset($_GET['id'])) {
        echo "Error, id not specified";
        exit;
    }

    $id = $_GET['id'];

    $arr = $crud->prepare_edit($_GET['module'], $id);
?>

<?php

$message = '';

if (isset($_POST['qrup_id'])) {

    $result = $crud->edit($_GET['module'], $_POST, $id);

    if ($result) {
        $message = <<<HTML
                <div class="alert alert-success" role="alert">
                    Update successfully | You will be redirected after 3 seconds
                </div>
                <script>
                    setTimeout(function() {
                      location.href="index.php?module={$_GET['module']}&action=list";
                    }, 3000);
                </script>
HTML;
    } else {
        $message = <<<HTML
                <div class="alert alert-danger" role="alert">
                    SQL error occured
                </div>
HTML;
    }

}

?>

<h1 class="page-header" style="text-transform: capitalize;">Update <?=$_GET['module'];?></h1>
<form action="" method="post" class="formgroup">
    <div class="form-group">
        <label>Universitet</label>
        <select id="uni" class="form-control">
            <?=$crud->get_data_for_select('uniler','ad');?>
        </select>
    </div>
    <div class="form-group">
        <label>Qrup</label>
        <select name="qrup_id" id="qrup" class="form-control">
            <?=$crud->get_data_for_select('qruplar','ad', $arr['qrup_id']);?>
        </select>
    </div>
    <div class="form-group">
        <label>Fenn</label>
        <select name="fenn_id" class="form-control">
            <?=$crud->get_data_for_select('fennler','ad', $arr['fenn_id']);?>
        </select>
    </div>
    <div class="form-group">
        <label>Bitis vaxti</label>
        <input type="date" class="form-control" name="finish_date" value="<?=$arr['finish_date'];?>">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Update</button>
    </div>

    <?= $message; ?>
</form>