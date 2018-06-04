<?php
session_start();
ob_start();

//ini_set('display_errors','0');

require 'classes/autoload.php';

$main->send_ip(0);

$result = '';

if (isset($_POST['login'])) {
    $username  = $_POST['username'];
    $password = $_POST['password'];
    if (isset($_POST['remember'])) {
        $remember = 1;
    }
    else {
        $remember = 0;
    }

    $login = $main->login($username, $password, $remember);

    if ($login) {
        $result = <<<HTML
            <script>
                location.href = "index.php";
            </script>
HTML;
    }
    else {
        $result = <<<HTML
            <div class="alert alert-danger" role="alert">
                İstifadəçi adı və ya şifrə yanlışdır!
            </div>
HTML;
    }
}
if (isset($_POST['register'])) {
    unset($_POST['register']);
    $registr = $main->register($_POST);

    if (!$registr) {
        $main->group_minus($_POST['qrup_id']);
        $result = <<<HTML
            <div class="alert alert-success" role="alert">
                Qeydiyyat uğurla sona çatdı!
            </div>
HTML;
    }
    else {
        switch ($registr) {
            case 1: $result = <<<HTML
            <div class="alert alert-danger" role="alert">
                Bu istifadəçi adı və ya email sistemdə mövcuddur!
            </div>
HTML;
            break;
            case 2: $result = <<<HTML
            <div class="alert alert-danger" role="alert">
                Seçdiyiniz qrupda qeydiyyat üçün yer yoxdur!
            </div>
HTML;
            break;
            case 4: $result = <<<HTML
            <div class="alert alert-danger" role="alert">
                Qrup şifrəsi səhvdir!
            </div>
HTML;
            break;
            default: $result = <<<HTML
            <div class="alert alert-danger" role="alert">
                Səhv baş verdi!
            </div>
HTML;
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<!--    <link rel="shortcut icon" href="--><?//=$info['icon'];?><!--" type="image/x-icon" />-->

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/main.css">

    <script src="js/jquery-3.2.1.min.js"></script>

    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title style="text-transform: capitalize;">OnlinExam | Login</title>

</head>

<body class="body-Login-back">

    <div class="container">
        <div class="row">
            <div class="w3_login">
                <h3 style="text-align: center; color: #212121; padding-bottom: .5em; position: relative;">Giriş &amp; Qeydiyyat</h3>
                <div class="w3_login_module">
                    <div class="module form-module">
                        <div class="toggle"><i style="line-height: 30px;" class="fa fa-times fa-pencil"></i>
                            <div class="tooltip">Qeydiyyat</div>
                        </div>
                        <div class="form">
                            <h2>Giriş edin</h2>
                            <form action="" method="post">
                                <input type="hidden" name="login" value="1">
                                <input type="text" name="username" placeholder="İstifadəçi adı" required=" ">
                                <input type="password" name="password" placeholder="Şifrə" required=" ">
                                <div class="checkbox" title="Əgər, seçili olarsa siz hesabdan çıxış vermədikcə, 1 həftə müddətində sistem tərəfindən avtomatik olaraq tanınacaqsınız...">
                                    <label>
                                        <input name="remember" type="checkbox">Məni yadda saxla
                                    </label>
                                </div>
                                <input type="submit" value="Giriş">
                            </form>
                        </div>
                        <div class="form">
                            <h2>Yeni hesab yaradın</h2>
                            <form action="" method="post">
                                <input type="hidden" name="register" value="1">
                                <input type="text" name="name" placeholder="Ad" required=" ">
                                <input type="text" name="surname" placeholder="Soyad" required=" ">
                                <input type="email" name="email" placeholder="Email" required=" ">
                                <input type="text" name="username" placeholder="İstifadəçi adı" required=" ">
                                <input type="password" name="password" placeholder="Şifrə" required=" ">
                                <select id="uni">
                                    <?=$main->get_data_for_select('uniler','ad', 0,'','Universitet');?>
                                </select>
                                <select name="qrup_id" id="qrup">
                                    <option value="">Qrup</option>
                                </select>
                                <input type="text" name="pass" placeholder="Qrup şifrəsi" required=" ">
                                <input type="submit" value="Qeydiyyat">
                            </form>
                        </div>
                        <?=$result;?>
                    </div>
                </div>
                <script>
                    //get groups
                    $("#uni").change(function(){
                        var uni = $(this).val();

                        console.log('ada');
                        //ajax
                        $.post("ajax/getgroups.php", {uni: uni}, function (data) {
                            $("#qrup").html(data);
                            //alert(data);
                            return false;
                        });
                    });

                    $('.toggle').click(function(){
                        // Switches the Icon
                        $(this).children('i').toggleClass('fa-pencil');
                        // Switches the forms
                        $('.form').animate({
                            height: "toggle",
                            'padding-top': 'toggle',
                            'padding-bottom': 'toggle',
                            opacity: "toggle"
                        }, "slow");
                    });
                </script>
            </div>
        </div>
    </div>
</body>
</html>
