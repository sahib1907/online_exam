<?php

class Db
{

    public $db;
    public $dbhost = 'localhost';
    public $dbname = 'quiz';
    public $dbuser = 'root';
    public $dbpass = '';


    function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        $this->db = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname, $this->dbuser, $this->dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        if (!$this->db) {
            echo "Connection error...";
        }
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function fetch($query)
    {
        if (!$query) {
            echo "SQL is not properly writed!";
            return false;
        }
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function numRows($query)
    {
        return $query->rowCount();
    }

//    public function security($string) {
////        $string = str_replace('<', "&lt;", $string);
////        $string = str_replace('>', "&gt;", $string);
//        $string = str_replace("'", "&#39;", $string);
//        $string = str_replace('"', "&quot;", $string);
////        $string = str_replace("&", "&amp;", $string);
//        $string = htmlspecialchars($string);
//        return $string;
//    }

    public function security($string) {
        return addslashes($string);
    }

}

?>