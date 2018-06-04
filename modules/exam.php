<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 27.05.2017
 * Time: 14:51
 */

include '../blocks/header.php';

$startTime = time();

$user = '';

if (isset($_SESSION['user']) && $_SESSION['user']==$_GET['u'] && isset($_SESSION['fenn']) && $_SESSION['fenn']==$_GET['p'] && isset($_GET['p']) && !empty($_GET['p']) && isset($_GET['c']) && !empty($_GET['c']) && $_GET['c']>0 && isset($_GET['u']) && !empty($_GET['u'])) {
    $fenn_id = $_GET['p'];
    $sual_sayi = $_GET['c'];
    $user = $_GET['u'];
    $int = $_GET['i'];
    if ($int != 'f') {
        $intArr = explode('-', $int);
        $s = $intArr['0'];
        $f = $intArr['1'];

        if ($f != 'f') {
            $interval = "AND `id`>={$s} AND `id`<={$f}";
        }
        else {
            $interval = "AND `id`>={$s}";
        }
    }
    else {
        $interval = '';
    }
}
else {
    echo <<<HTML
        <script>
            location.href="/404.php";
        </script>
HTML;
    exit;
}


$sualArr = $main->show_list('suallar', "WHERE `fenn_id`={$fenn_id} {$interval} ORDER BY rand() LIMIT 0,{$sual_sayi}");
$c = count($sualArr);

?>

<a href="/index.php" class="btn btn-primary" style="margin-left: 3%;">Ana səhifəyə keç...</a>
<spam class="btn btn-primary" style="float: right; margin-right: 3%; text-transform: capitalize;"><?=$user;?></spam>
<div class="exam">
    <form action="result.php?u=<?=$user;?>&c=<?=$c;?>" method="post">
        <input type="hidden" name="finished" value="1">
        <input type="hidden" name="d1" value="<?=$startTime;?>">

<?php

for ($i=0; $i<count($sualArr); $i++) {
    $num = $i + 1;
    $result = <<<HTML
        <div class="form-group">
            <label><spam style="color: red;">{$num}.</spam> {$sualArr[$i]['ad']}</label>
HTML;

    $cavabArr = $crud->show_list('cavablar', "WHERE `sual_id`={$sualArr[$i]['id']} ORDER BY rand()");
    $cavabSay = $crud->show_list('cavablar', "WHERE `sual_id`={$sualArr[$i]['id']} AND `duzgun` = 1");
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

include '../blocks/footer.php';

?>

