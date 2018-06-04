<?php

$message = '';

if (isset($_POST['name'])) {

    $result = $crud->add($_GET['module'], $_POST);

    if ($result) {
        $update_group = $crud->group_minus($_POST['qrup_id']);
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
        <input type="text" class="form-control" name="name">
    </div>
    <div class="form-group">
        <label>Soy ad</label>
        <input type="text" class="form-control" name="surname">
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="email">
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password">
    </div>
    <div class="form-group">
        <label>Universitet</label>
        <select id="uni" class="form-control">
            <?=$crud->get_data_for_select('uniler','ad');?>
        </select>
    </div>
    <div class="form-group">
        <label>Qrup</label>
        <select name="qrup_id" id="qrup" class="form-control">
            <option value="">Select</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Add</button>
    </div>

    <?= $message; ?>
</form>