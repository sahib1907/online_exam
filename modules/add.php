<?php


include '../blocks/header.php';

if (isset($_GET['p']) && !empty($_GET['p'])) {
    $fenn_id = $_GET['p'];
}
else {
    echo <<<HTML
                <script>
                    location.href="/404.php";
                </script>
HTML;
    exit;
}

$message = '';

if (isset($_POST['sual'])) {

    $sual = $_POST['sual'];

    $suallar['ad'] = $sual;
    $suallar['fenn_id'] = $_GET['p'];
    $cavablar['fenn_id'] = $_GET['p'];

    $result = $crud->add('suallar', $suallar);

    $select = $crud->show_list('suallar', "WHERE `ad`='{$sual}' ORDER BY `id` DESC");
    $sual_id = $select[0]['id'];
    $cavablar['sual_id'] = $sual_id;

    $answer = $_POST['cavab'];
    $count =  count($answer);

    $answers = '';

    for ($i=0;$i<$count;$i++) {
        if (empty($answer[$i])) {
            continue;
        }
        if ($i == 0) {
            $cavablar['duzgun'] = 1;
        }
        else {
            $cavablar['duzgun'] = 0;
        }
        $cavablar['ad'] = $answer[$i];
        $addAns = $crud->add('cavablar', $cavablar);
    }

    if ($result) {
        $message = <<<HTML
                <div class="alert alert-success" role="alert" style="display:block;">
                    Sual əlavə edildi...
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

<a href="/index.php" class="btn btn-primary" style="margin: 10px 0 15px 10%;">Ana səhifəyə keç...</a>
<form action="" method="post" class="formgroupAdd">
    <div class="form-group">
        <label>Sual</label>
        <textarea class="form-control" name="sual" cols="30" rows="3" style="resize: none;"></textarea>
    </div>
    <div class="form-group">
        <label>Cavab 1 <strong>(Düzgün cavab)</strong></label>
        <input type="text" class="form-control" name="cavab[]" id="1" style="background-color: #43ff9c;">
    </div>
    <div class="form-group">
        <label>Cavab 2</label>
        <input type="text" class="form-control" name="cavab[]" id="2">
    </div>
    <div class="form-group">
        <label>Cavab 3</label>
        <input type="text" class="form-control" name="cavab[]" id="3">
    </div>
    <div class="form-group">
        <label>Cavab 4</label>
        <input type="text" class="form-control" name="cavab[]" id="4">
    </div>
    <div class="form-group">
        <label>Cavab 5</label>
        <input type="text" class="form-control" name="cavab[]" id="5">
    </div>
    <input type="hidden" value="5" id="hidden">
    <div class="addAns">

    </div>

    <div class="form-group">
        <a href="#" class="addAnswer btn btn-info">Yeni sual üçün boş xana əlavə et</a>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success">Sualı əlavə et</button>
    </div>

    <?= $message; ?>
</form>

<?php

include '../blocks/footer.php';

?>


