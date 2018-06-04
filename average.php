<?php

include 'blocks/header.php';

$message = '';

if (isset($_POST['submit'])) {

    $c = count($_POST['credit']);
    $credits = $_POST['credit'];
    $points = $_POST['point'];

    $points_sum = 0;
    $credits_sum = 0;
    $average = 0;

    for ($i=0; $i<$c; $i++) {
        $credits_sum += $credits[$i];
        $points_sum += $credits[$i]*$points[$i];
    }

    $average = $points_sum/$credits_sum;

    if ($average > 100) {
        $average = 0;
    }

    $arr['average'] = $average;
    $arr['client_id'] = $_SESSION['client_id'];
    $arr['date'] = $main->get_current_date();

    $add = $main->add('averages', $arr);

    if ($add) {
        $message = <<<HTML
            <div class="alert alert-success" role="alert" style="display:block;">
                Sizin ortalamanız: {$average}
            </div>
HTML;
    } else {
        $message = <<<HTML
            <div class="alert alert-danger" role="alert" style="display:block;">
                Səhv baş verdi!
            </div>
HTML;
    }

}

?>
<a href="/index.php" class="btn btn-primary" style="margin: 10px 0 15px 10%;">Ana səhifə</a>
<a href="/averageratings.php" class="btn btn-primary" style="margin: 10px 10% 15px 0; float: right;">Ortalama sıralaması</a>
<?= $message; ?>
<form action="" method="post" class="formgroupAdd">
    <input type="hidden" name="submit" value="1">
    <div id="fenn1">
        <label style="color: #ec1d38;"><strong>FƏNN 1</strong></label>
        <div class="form-group">
            <label>Kredit sayı:</label>
            <input type="number" class="form-control" name="credit[]" required>
        </div>
        <div class="form-group">
            <label>Bal:</label>
            <input type="number" class="form-control" name="point[]" required>
        </div>
        <hr>
    </div>
    <div id="fenn2">
        <label style="color: #ec1d38;"><strong>FƏNN 2</strong></label>
        <div class="form-group">
            <label>Kredit sayı:</label>
            <input type="number" class="form-control" name="credit[]" required>
        </div>
        <div class="form-group">
            <label>Bal:</label>
            <input type="number" class="form-control" name="point[]" required>
        </div>
        <hr>
    </div>
    <div id="fenn3">
        <label style="color: #ec1d38;"><strong>FƏNN 3</strong></label>
        <div class="form-group">
            <label>Kredit sayı:</label>
            <input type="number" class="form-control" name="credit[]" required>
        </div>
        <div class="form-group">
            <label>Bal:</label>
            <input type="number" class="form-control" name="point[]" required>
        </div>
        <hr>
    </div>
    <input type="hidden" value="3" id="hidden">
    <div class="addAns">

    </div>

    <div class="form-group">
        <a href="#" class="addAnswer btn btn-info">Yeni fənn üçün boş xana əlavə et</a>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Hesabla</button>
    </div>
</form>

<?php

include 'blocks/footer.php';

?>


