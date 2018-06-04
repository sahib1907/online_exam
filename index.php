<?php
include 'blocks/header.php';

if (isset($_POST['islike'])) {
    $_POST['user_id'] = $_SESSION['client_id'];
    $_POST['date'] = $main->get_current_date();
    $main->add('inquiry', $_POST);
}

if (isset($_GET['exam']) && $_GET['exam'] == 'no') {
    echo <<<HTML
        <script >
            alert("İmtahana giriş uğursuz oldu. Bütün məlumatları düzgün yazdığınızdan və imthana giriş hüququnuz olduğundan emin olun!");
        </script>
HTML;
}

$c_id = $_SESSION['client_id'];

$selectOptions = $main->get_exams($c_id);
$countOptions =  count($selectOptions);
$options = '';

for ($i=0; $i<$countOptions; $i++) {
    $options .= <<<HTML
        <option value="{$selectOptions[$i]['id']}">{$selectOptions[$i]['fenn']}</option>
HTML;
}

$selectRatings = $main->show_list_for_ratings(''," LIMIT 0,7");
$ratings = '';

for ($j=0; $j<count($selectRatings); $j++) {
    $ratings .= <<<HTML
        <li class="list-group-item"><spam style="left: 0; text-transform: capitalize;">{$selectRatings[$j]['ad']}</spam><spam style="float: right;">{$selectRatings[$j]['rating']}</spam></li>
HTML;
}

?>


    <div class="container" style="width: 100% !important; padding: 0 !important;">
        <div class="panel panel-default" style=" background-color: #d9edf7 !important;">
            <a class="btn btn-warning" href="https://www.facebook.com/sahib.fermanli" style="margin: 5px;">Əlaqə</a>
            <a class="btn btn-warning" href="logout.php" style="margin: 5px;">Çıxış</a>
        </div>
    </div>

<div style="margin-top: 5%; display: block;">

    <div class="col-lg-3 formDiv">
        <form action="" method="post" class="formgroup">
            <legend>Rəy bildir</legend>
            <div class="alert alert-info" role="alert" style="display: block;">
                Fikirleriniz bizim üçün dəyərlidir   <i style="color: tomato; font-size: 18px;" class="fa fa-smile-o" aria-hidden="true"></i>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <label>Saytı bəyənirsinizmi?</label><br>
                    <label for="s1">Hə</label>&nbsp;<input type="radio" name="islike" id="s1" value="1" checked>&nbsp;
                    <label for="s2">Yox</label>&nbsp;<input type="radio" name="islike" id="s2" value="0">&nbsp;
                </div>
                <br>
                <div class="input-group">
                    <label>Sayta 5 xallıq sistemlə səs verin:</label><br>
                    <label for="p1">1</label>&nbsp;<input type="radio" name="point" id="p1" value="1">&nbsp;
                    <label for="p2">2</label>&nbsp;<input type="radio" name="point" id="p2" value="2">&nbsp;
                    <label for="p3">3</label>&nbsp;<input type="radio" name="point" id="p3" value="3">&nbsp;
                    <label for="p4">4</label>&nbsp;<input type="radio" name="point" id="p4" value="4">&nbsp;
                    <label for="p5">5</label>&nbsp;<input type="radio" name="point" id="p5" value="5" checked>
                </div>
                <br>
                <div>
                    <label>Tələb və tövsiyyələriniz:</label>
                    <textarea class="form-control" name="message" cols="30" rows="10" style="resize: none;"></textarea>
                </div>
            </div>
            <div class="form-group" style="text-align: center;">
                <div class="input-group right-side">
                    <button class="btn btn-success">Göndər</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-3 formDiv">
        <div class="formgroup">
            <legend>Ortalama ballar</legend>
            <div class="alert alert-info" role="alert" style="display: block;">
                İmtahanlarınız bitib?<br>
                Elə isə ortalama balınızı hesablayın :)
            </div>
            <div class="input-group right-side">
                <a href="average.php" class="btn btn-success" style="float: left;">Hesabla</a>
                <a href="averageratings.php" class="btn btn-success" style="float: right;">Siyahı</a>
            </div>
        </div>
    </div>
<!--    <div class="col-lg-2"></div>-->

    <div class="col-lg-3 formDiv">
        <form action="exam.php" method="post" class="formgroup">
            <legend>İmtahan</legend>
            <div class="alert alert-info" role="alert" style="display: block;">
                <strong>Qeyd:</strong> Bütün xanalar doldurulmalıdır!
            </div>
            <input type="hidden" name="examselected" value="1">
            <div class="form-group">
                <div class="input-group">
                    <select class="form-control" name="exam_id" id="selectFenn" onchange="changeValue()">
                        <option value="0">
                            Imtahan seçin
                        </option>
                        <?=$options;?>
                    </select>
                </div>
                <br>
                <label>
                    Sualların düşmə aralığını daxil edin:<br>
                    (Susmaya göre bütün suallar seçilir)
                </label>
                <div id="interval">
                    <div class="input-group">
                        <input class="form-control" type="number" name="hardan" placeholder="hardan" required>
                    </div>
                    <br>
                    <div class="input-group">
                        <input class="form-control" type="number" name="hara" placeholder="hara" required>
                    </div>
                    <br>
                    <div class="input-group">
                        <input class="form-control" type="number" name="say" placeholder="imtahanda neçə sual olsun?" required>
                    </div>
                    <br>
                </div>
            </div>
            <div class="form-group" style="text-align: center;">
                <div class="input-group right-side">
                    <button class="btn btn-success">Başla</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-3 formDiv">
        <div class="formgroup">
            <legend>Rating</legend>
            <ul class="list-group">
                <?=$ratings;?>
            </ul>
            <div class="input-group right-side" style="text-align: center;">
                <a href="ratings.php" class="btn btn-success">Tam siyahı</a>
            </div>
        </div>
    </div>

<!--    <div class="col-lg-2"></div>-->
</div>
<?php

include 'blocks/footer.php';

?>