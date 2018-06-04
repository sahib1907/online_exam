<br>
<br>
<br>

<!--<a href="/fdow.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow</a>-->
<!--<a href="/fdowselect.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow Select</a>-->
<!--<a href="/fdowatu.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow Atu</a>-->
<!--<a href="/fdowatuselect.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow Atu Select</a>-->
<!--<a href="/fdowplus.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow Plus</a>-->
<!--<a href="/fdowanswer.php" class="btn btn-info" style="display: block; margin-bottom: 5px;">Fdow Answer</a>-->

<?php

$modules = scandir('../');

unset($modules[0], $modules[1]);

$menu = '';

$arr = array(
        '404.php',
        'add.php',
        'average.php',
        'averageratings.php',
        'exam.php',
        'index.php',
        'login.php',
        'logout.php',
        'ratings.php',
        'result.php',
);
foreach ($modules as $value) {

    if (substr($value, 0, 4) != 'fdow' || substr($value, strlen($value)-3) != 'php') {
        continue;
    }

    $menu .= <<<HTML
                <a href="/{$value}" class="btn btn-info" style="display: block; margin-bottom: 5px;">{$value}</a>
HTML;
}

echo $menu;