<?php

/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 25.04.2017
 * Time: 13:49
 */
class Session extends Db
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($username, $password, $remember)
    {
        $username = $this->security($username);
        $password = $this->security($password);

        $sql = "SELECT * FROM `users` WHERE `username`='{$username}' AND `password`='{$password}'";
        $query = $this->query($sql);

        $result = $this->numRows($query);

        if ($result > 0) {
            $_SESSION['username'] = $username;

            if ($remember == 1) {
                setcookie('username', $username, time()+7*24*3600);
                setcookie('user_pass', $password, time()+7*24*3600);
            }

            return true;
        }
        else {
            return false;
        }
    }

    public function checkUser()
    {
        if (isset($_COOKIE['username']) && !empty($_COOKIE['username']) && isset($_COOKIE['user_pass']) && !empty($_COOKIE['user_pass'])) {
            $queryCookie = $this->query("SELECT * FROM `users` WHERE username = '{$_COOKIE['username']}' AND `password`='{$_COOKIE['user_pass']}' AND `deleted`=0");
            if ($this->numRows($queryCookie) == 1) {
                if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
                    $_SESSION['username'] = $this->security($_COOKIE['username']);
                }
                setcookie('username', $_COOKIE['username'], time()+7*24*3600);
                setcookie('user_pass', $_COOKIE['user_pass'], time()+7*24*3600);
                return true;
            }
            else {
                setcookie("username", "0", time() - (3600*24*7));
                setcookie("user_pass", "0", time() - (3600*24*7));
                return false;
            }
        }
        else {
            $query = $this->query("SELECT * FROM `users` WHERE username = '{$_SESSION['username']}' AND `deleted`=0");

            if (strlen($_SESSION['username']) == 0 || $this->numRows($query) == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

}

$session = new Session();