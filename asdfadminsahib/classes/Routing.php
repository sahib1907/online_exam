<?php

/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 25.04.2017
 * Time: 13:49
 */
class Routing extends Db
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_path($module, $action) {
        $path = $_SERVER['DOCUMENT_ROOT'].'/asdfadminsahib/modules/'.$module."/".$action.".php";

        if(!file_exists($path)) {
            $path = $_SERVER['DOCUMENT_ROOT'].'/asdfadminsahib/modules/default/404.php';
        }

        return $path;
    }

    public function generate_menu($currentModule) {
        $modules = scandir('modules');

        unset($modules[0], $modules[1]);

        $active = '';
        $menu = '';

        foreach ($modules as $value) {
            if($value == 'default') {
                continue;
            }

            if ($value == $currentModule) {
                $active = 'selected';
            }
            else {
                $active = '';
            }

            $menu .= <<<HTML
                <li class="{$active}">
                    <a href="index.php?module={$value}&action=list" class="contentMenu">{$value}</a>
                </li>
HTML;
        }

        return $menu;
    }
}

$routing = new Routing();