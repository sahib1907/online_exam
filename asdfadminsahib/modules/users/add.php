<?php

$message = '';

if (isset($_POST['username'])) {

    if (strlen($_POST['password']) < 8 || strlen($_POST['username']) < 4) {
        $message = <<<HTML
                <div class="alert alert-danger" role="alert">
                    Password must be at least 8 symbols! OR The username must be in the range of 4-50 symbols!
                </div>
HTML;
        exit;
    }

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
<form action="" method="post" class="formgroup" onsubmit="return validateForm();">
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" id="username">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" id="password">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Add</button>
    </div>

    <?= $message; ?>
</form>