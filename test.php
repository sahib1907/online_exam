<?php
/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 27.05.2018
 * Time: 19:28
 */


$res = true;

$message = '';

include 'classes/autoload.php';

if (!empty($_FILES['txtfile'])) {
    $file = file($_FILES['txtfile']['tmp_name']);

    for ($i=0; $i<count($file); $i++) {
        echo $file[$i];
    }
}





include 'blocks/header.php';

?>

    <form action="" method="post" class="formgroupAdd" enctype="multipart/form-data">
        <div class="form-group">
            <label>Sual</label>
            <input type="file" class="form-control" name="txtfile">
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Sualları əlavə et</button>
        </div>

        <?= $message; ?>
    </form>

<?php
include 'blocks/footer.php';
?>