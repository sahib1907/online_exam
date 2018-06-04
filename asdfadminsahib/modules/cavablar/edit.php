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
        <label>Ad</label>
        <textarea name="ad" class="form-control" cols="30" rows="10"><?=$arr['ad'];?></textarea>
    </div>
    <div class="form-group">
        <label>Duzgun ?</label>
        <select name="duzgun" class="form-control">
            <?php
            if ($arr['duzgun'] == 0) {
                echo <<<HTML
                    <option selected value="0">No</option>
                    <option value="1">Yes</option>
HTML;
            }
            else {
                echo <<<HTML
                    <option value="0">No</option>
                    <option selected value="1">Yes</option>
HTML;
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Sual</label>
        <select name="sual_id" class="form-control">
            <option value="0">Select option</option>
            <?=$crud->get_data_for_select('suallar', 'ad', $arr['sual_id'])?>
        </select>
    </div>
    <div class="form-group">
        <label>Fenn</label>
        <select name="fenn_id" class="form-control">
            <option value="0">Select option</option>
            <?=$crud->get_data_for_select('fennler', 'ad', $arr['fenn_id'])?>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Update</button>
    </div>

    <?= $message; ?>
</form>