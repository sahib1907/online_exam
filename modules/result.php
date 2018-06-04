<?php
/**
 * Created by PhpStorm.
 * User: Sahib Fermanli
 * Date: 27.05.2017
 * Time: 19:56
 */

include '../blocks/header.php';
$_SESSION['user'] = '';
$_SESSION['fenn'] = '';
$_SESSION['d1'] = '';
//session_destroy();

if (isset($_POST['finished']) && !empty($_POST['finished'])) {
    unset($_POST['finished']);

    $d1 = $_POST['d1'];
    unset($_POST['d1']);
    $d2 = time();

    $user = 'User';
    $say = 1;

    $d =  $d2-$d1;
    $h = floor($d/3600); //saat
    $d = $d - $h*3600;
    $m = floor($d/60); //deqiqe
    $d = $d - $m*60;
    $s = $d; //saniye
    $time = $h." saat, ".$m." dəqiqə, ".$s." saniyə";

    if (isset($_GET['u']) && !empty($_GET['u']) && isset($_GET['c']) && !empty('c')) {
        $user = $_GET['u'];
        $say = $_GET['c'];
    }

    $f_id = 0;
    $correctCount = 0;
    $inCorrectCount = 0;
    $incorrects = '';


    foreach ($_POST as $key=>$value) {

        if (is_array($value)) {
            $sizinCavab = '';
            $cavabSay = $main->show_list('cavablar', "WHERE `sual_id`={$key} AND `duzgun` = 1");
            if (count($value) == count($cavabSay)) {
                $bool = true;
                for ($i=0; $i<count($value); $i++) {
                    $check = $main->show_list('cavablar', "WHERE `id`={$value[$i]}");
                    if ($check[0]['duzgun'] == 0) {
                        $bool = false;
                        $sizinCavab .= "{$check[0]['ad']}<br>";
                    }
                    else {
                        $sizinCavab .= "{$check[0]['ad']}<br>";
                    }
                }
                if ($bool == true) {
                    $correctCount++;
                }
                else {
                    $inCorrectCount++;
                    $question = $main->show_list('suallar', "WHERE `id`={$key}");
                    $correctAns = $main->show_list('cavablar', "WHERE `sual_id`={$key} AND `duzgun`=1");
                    $duzgunCavab = '';
                    for ($i=0; $i<count($correctAns); $i++) {
                        $duzgunCavab .= "{$correctAns[$i]['ad']}<br>";
                    }
                    $incorrects .= <<<HTML
                        <div class="alert alert-danger" role="alert" style="display: block; margin: 10px 3%;">
                            <strong>Sual:</strong> {$question[0]['ad']}<br>
                            <strong>Sizin cavablarınız:</strong> {$sizinCavab}
                            <strong>Düzgün cavablar:</strong> {$duzgunCavab}
                        </div>
HTML;
                }
            }
            else {
                $inCorrectCount++;
                for ($i=0; $i<count($value); $i++) {
                    $check = $main->show_list('cavablar', "WHERE `id`={$value[$i]}");
                    $sizinCavab .= "{$check[0]['ad']}<br>";
                }
                $question = $main->show_list('suallar', "WHERE `id`={$key}");
                $correctAns = $main->show_list('cavablar', "WHERE `sual_id`={$key} AND `duzgun`=1");
                $duzgunCavab = '';
                for ($i=0; $i<count($correctAns); $i++) {
                    $duzgunCavab .= "{$correctAns[$i]['ad']}<br>";
                }
                $incorrects .= <<<HTML
                    <div class="alert alert-danger" role="alert" style="display: block; margin: 10px 3%;">
                        <strong>Sual:</strong> {$question[0]['ad']}<br>
                        <strong>Sizin cavablarınız:</strong> {$sizinCavab}
                        <strong>Düzgün cavablar:</strong> {$duzgunCavab}
                    </div>
HTML;
            }
        }
        else {
            $check = $main->show_list('cavablar', "WHERE `id`={$value}");
            $f_id = $check[0]['fenn_id'];

            if ($check[0]['duzgun'] == 1) {
                $correctCount++;
            }
            else if ($check[0]['duzgun'] == 0) {
                $inCorrectCount++;
                $question = $main->show_list('suallar', "WHERE `id`={$key}");
                $correctAns = $main->show_list('cavablar', "WHERE `sual_id`={$key} AND `duzgun`=1");
                $incorrects .= <<<HTML
                    <div class="alert alert-danger" role="alert" style="display: block; margin: 10px 3%;">
                        <strong>Sual:</strong> {$question[0]['ad']}<br>
                        <strong>Sizin cavabınız:</strong> {$check[0]['ad']}<br>
                        <strong>Düzgün cavab:</strong> {$correctAns[0]['ad']}
                    </div>
HTML;
            }
        }
    }

    echo <<<HTML
        <a href="/index.php" class="btn btn-primary" style="margin: 20px 3% 15px">Ana səhifəyə keç...</a>
        <div class="alert alert-info" role="alert" style="display: block; margin: 20px 3%;">
            <strong style="text-transform: capitalize;">{$user},</strong>
            <strong>sizin nəticəniz:</strong> {$correctCount} düz; {$inCorrectCount} səhv <br>
            <strong>İmtahan müddəti:</strong> {$time}
        </div>
HTML;
    echo $incorrects;


    $arr['duz'] = $correctCount;
    $arr['sehv'] = $inCorrectCount;
    $arr['ad'] = $user;
    $correctCount = $correctCount - $inCorrectCount/4;
    if ($correctCount < 0) {
        $correctCount = 0;
    }
    $arr['rating'] = round(($correctCount/$say)*100);
    $arr['fenn_id'] = $f_id;
    $arr['vaxt'] = $d2-$d1;

    $main->add('ratings', $arr);

    include '../blocks/footer.php';
}
//else {
//    echo <<<HTML
//        <script>
//            location.href="/404.php";
//        </script>
//HTML;
//    exit;
//}