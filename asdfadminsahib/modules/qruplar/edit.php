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

if (isset($_POST['ad'])) {

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
        <select name="uni_id" class="form-control">
            <?=$crud->get_data_for_select('uniler','ad', $arr['uni_id']);?>
        </select>
    </div>
    <div class="form-group">
        <label>Qrup adı:</label>
        <input type="text" class="form-control" name="ad" value="<?=$arr['ad'];?>">
    </div>
    <div class="form-group">
        <label>Qrup sifresi:</label>
        <input type="text" class="form-control" name="pass" value="<?=$arr['pass'];?>">
    </div>
    <div class="form-group">
        <label>Telebe sayı:</label>
        <input type="number" class="form-control" name="say" value="<?=$arr['say'];?>">
    </div>
    <div class="form-group">
        <label>Dolu yerler:</label>
        <input type="number" class="form-control" name="dolu" value="<?=$arr['dolu'];?>">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Update</button>
    </div>

    <?= $message; ?>
</form>