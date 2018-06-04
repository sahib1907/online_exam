<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 27.05.2017
 * Time: 14:51
 */

include 'blocks/header.php';

$c_id = $_SESSION['client_id'];
$sualArr = array();
$c = 0;

if ($_POST['exam_id'] != 0 && !empty($_POST['say']) && $_POST['say']>0 && !empty($_POST['hardan']) && $_POST['hardan']>0 && !empty($_POST['hara']) && $_POST['hara']>=10 && !empty($_POST['start']) && ctype_digit($_POST['start'])) {
    $check_exam = $main->check_exam_for_user($c_id, $_POST['exam_id']);
    if (count($check_exam) == 1) {
        $fenn_id = $check_exam['fenn_id'];
    }
    else {
        header("Location: index.php?exam=no");
        exit;
    }

    $start = $_POST['start'];
    $hardan = $_POST['hardan']-1 + $start;
    $hara = $_POST['hara']-1 + $start;
    $interval = "AND `id`>={$hardan} AND `id`<={$hara}";

    $sual_sayi = $_POST['say'];

    $sualArr = $main->show_list('suallar', "AND `fenn_id`={$fenn_id} {$interval} ORDER BY rand() LIMIT 0,{$sual_sayi}");
    $c = count($sualArr);
}
else {
    header("Location: index.php?exam=no");
    exit;
}

$startTime = time();

?>

<a href="/index.php" class="btn btn-primary" style="margin-left: 3%;">Ana səhifəyə keç...</a>
<span class="btn btn-primary" style="float: right; margin-right: 3%; text-transform: capitalize;"><?=$check_user['name'].' '.$check_user['surname'];?></span>
<div class="exam">
    <form action="result.php" method="post">
        <input type="hidden" name="finished" value="1">
        <input type="hidden" name="d1" value="<?=$startTime;?>">
        <input type="hidden" name="count" value="<?=$c;?>">

<?php

for ($i=0; $i<count($sualArr); $i++) {
    $num = $i + 1;
    $result = <<<HTML
        <div class="form-group">
            <label><spam style="color: red;">{$num}.</spam> {$sualArr[$i]['ad']}</label>
HTML;

    $cavabArr = $main->show_list('cavablar', "AND `sual_id`={$sualArr[$i]['id']} ORDER BY rand()");
    $cavabSay = $main->show_list('cavablar', "AND `sual_id`={$sualArr[$i]['id']} AND `duzgun` = 1");
    if (count($cavabSay) > 1) {
        for ($j=0; $j<count($cavabArr); $j++) {
            $result .= <<<HTML
                <div class="checkbox">
                    <label><input type="checkbox" value="{$cavabArr[$j]['id']}" name="{$sualArr[$i]['id']}[]">{$cavabArr[$j]['ad']}</label>
                </div>
HTML;
        }
    }
    else {
        for ($j=0; $j<count($cavabArr); $j++) {
            $result .= <<<HTML
                <div class="radio">
                    <label><input type="radio" value="{$cavabArr[$j]['id']}" name="{$sualArr[$i]['id']}">{$cavabArr[$j]['ad']}</label>
                </div>
HTML;
        }
    }

    $result .= <<<HTML
        </div>
HTML;

    echo $result;
}

?>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Bitir</button>
        </div>
    </form>
</div>

<?php

include 'blocks/footer.php';

?>

