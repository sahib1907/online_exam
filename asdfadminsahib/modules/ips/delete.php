<?php
/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 25.04.2017
 * Time: 13:35
 */

if (!isset($_GET['id'])) {
    echo "Error, id not specified";
    exit;
}

$id = $_GET['id'];

$result = $crud->delete($_GET['module'], $id);

if ($result) {
    if ($_SESSION['selectuser'] == $_SESSION['username']) {
        echo "<script>location.href='logout.php'</script>";
    } else {
        echo "<script>location.href='index.php?module={$_GET['module']}&action=list'</script>";
    }
} else {
    echo <<<HTML
            <div class="alert alert-danger" role="alert">
                Error while delete!
                <a href="index.php">Return to main page</a>
            </div>
HTML;
}