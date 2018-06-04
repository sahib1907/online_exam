<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 28.05.2017
 * Time: 15:03
 */

$res = true;

$message = '';

include 'classes/autoload.php';

if (!empty($_FILES['txtfile'])) {
    if (substr($_FILES['txtfile']['name'], strlen($_FILES['txtfile']['name'])-3) == 'txt') {
        $file = file($_FILES['txtfile']['tmp_name']);

        $fenn['ad'] = substr($_FILES['txtfile']['name'], 0, -4);
        $fennAdd = $main->add('fennler', $fenn);
        $selectFenn = $main->show_list('fennler', "ORDER BY `id` DESC");
        $f_id = $selectFenn[0]['id'];

        $variantlar = array('a.', 'b.', 'c.', 'd.', 'e.');

        $suallar['fenn_id'] = $f_id;
        $cavablar['fenn_id'] = $f_id;
        $sual = '';
        $correctAnswer = '';
        $say = 0;

        for ($i=0; $i<count($file); $i++) {
            $s = $file[$i];
            $s = trim($s);
            if ($s[0] == '@') {
                $img = substr($s, 1);
                $s = "<img src='/images/{$selectFenn[0]['ad']}/{$img}' alt='{$img}' style='display: block; width: 100%; max-height: 300px;'>";
            }
            if (!in_array($s[0].$s[1], $variantlar) && !strstr($s, "Cavab") && strlen($s)>3) {
                $sual .= $s."<br>";
            }
            else if (in_array($s[0].$s[1], $variantlar)) {
                if ($s[0] == 'a') {
                    for ($j=$i; $j<count($file); $j++) {
                        $sAns = $file[$j];
                        $sAns = trim($sAns);
                        if (strstr($sAns, "Cavab")) {
                            $correctAnswer = trim(substr($sAns, 6));
                            break;
                        }
                    }
                    $suallar['ad'] = $sual;
                    $result = $main->add('suallar', $suallar);
                    if (!$result) {
                        $res = false;
                    }
                    $select = $main->show_list('suallar', "ORDER BY `id` DESC");
                    $sual_id = $select[0]['id'];
                    $cavablar['sual_id'] = $sual_id;
                    $sual = '';
                }
                if (trim(substr($s, 2)) == $correctAnswer) {
                    $cavablar['duzgun'] = 1;
                }
                else {
                    $cavablar['duzgun'] = 0;
                }
                $s = trim(substr($s, 2));
                $cavablar['ad'] = $s;
                $addAns = $main->add('cavablar', $cavablar);
                if (!$addAns) {
                    $res = false;
                }
            }
        }
    }
    else {
        $message .= "File formati duzgun deyil. (.txt formati secin)"."<br>";
    }
}

if ($res == true) {
    $message .=  "Ugurlu! Suallar bazaya yazilarken hec bir sehv bas vermedi..."."<br>";
}
else {
    $message .= "Sehv bas verdi!"."<br>";
}

include 'blocks/header.php';

?>

<a href="/index.php" class="btn btn-primary" style="margin: 10px 0 15px 10%;">Ana səhifəyə keç...</a><a href="/asdfadminsahib/index.php" class="btn btn-primary" style="margin: 10px 10% 15px 10%; float: right;">Admin panel...</a>
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


