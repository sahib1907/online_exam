<?php

$message = '';

if (isset($_POST['ad'])) {

    $_POST['pass'] = $crud->random_string();

    $result = $crud->add($_GET['module'], $_POST);

    if ($result) {
        $message = <<<HTML
                <div class="alert alert-success" role="alert">
                    Added successfully | You will be redirected after 3 seconds
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

<h1 class="page-header" style="text-transform: capitalize;">Add <?=$_GET['module'];?></h1>
<form action="" method="post" class="formgroup">
    <div class="form-group">
        <label>Universitet</label>
        <select name="uni_id" class="form-control">
            <?=$crud->get_data_for_select('uniler','ad');?>
        </select>
    </div>
    <div class="form-group">
        <label>Qrup adı:</label>
        <input type="text" class="form-control" name="ad">
    </div>
    <div class="form-group">
        <label>Telebe sayı:</label>
        <input type="number" class="form-control" name="say">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Add</button>
    </div>

    <?= $message; ?>
</form>