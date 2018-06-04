<?php

$message = '';

if (isset($_POST['ad'])) {

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
        <label>Ad</label>
        <textarea name="ad" class="form-control" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <label>Fenn</label>
        <select name="fenn_id" class="form-control">
            <option value="0">Select option</option>
            <?=$crud->get_data_for_select('fennler', 'ad')?>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Add</button>
    </div>

    <?= $message; ?>
</form>