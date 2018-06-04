<?php

class Main extends Db
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_security($string) {
        return $this->security($string);
    }

    public function get_current_date() {
        return date('Y-m-d H:i:s',time()+14560);
    }

    public function send_ip($user_id) {
        $date = $this->get_current_date();
        $ip = $_SERVER['REMOTE_ADDR'];
        $location = $_SERVER['SCRIPT_NAME'];
        $sql = "INSERT INTO `ips` (`ip`, `user_id`, `location`, `date`) VALUES ('{$ip}', {$user_id}, '{$location}', '{$date}')";

        return $this->query($sql);
    }

    public function add($table, $array)
    {
        $keys = '';
        $values = '';

        $table = $this->security($table);

        foreach ($array as $key => $value) {
            $value = $this->security($value);
            if ($key == 'password') {
                $value = sha1(substr(md5($value), 3, -8));
            }
            $keys .= "`" . $key . "`,";
            $values .= "'" . $value . "',";
        }

        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";
//echo $sql."<br><br>";
        return $this->query($sql);
    }

    public function prepare_edit($table, $id)
    {
        $id = $this->security($id);
        $table = $this->security($table);

        $sql = "SELECT * FROM `{$table}` WHERE `id`='{$id}'";
        $query = $this->query($sql);

        $q = $this->fetch($query);

        return $q;
    }

    public function edit($table, $array, $id)
    {
        $id = $this->security($id);
        $table = $this->security($table);

        $sql1 = "SELECT * FROM `{$table}` WHERE id = '{$id}'";
        $query1 = $this->query($sql1);
        $q = $this->fetch($query1);

        if ($this->numRows($query1) > 0) {
            if ($q['username'] == $_SESSION['username']) {
                $_SESSION['username'] = $array['username'];
            }
            $updatestring = "";
            foreach ($array as $key => $value) {
                $value = $this->security($value);
                if ($key == 'password') {
                    $value = sha1(substr(md5($value), 3, -8));
                }
                $updatestring .= "`{$key}`='{$value}',";
            }
            $updatestring = substr($updatestring, 0, -1);

            $sql = "UPDATE `{$table}` SET {$updatestring} WHERE `id`='{$id}'";

            return $this->query($sql);
        } else {
            return false;
        }
    }

    public function get_data_for_select($table, $column, $id = 0, $where='', $select='Select')
    {
        $id = $this->security($id);
        $table = $this->security($table);
        $column = $this->security($column);

        $sql = "SELECT * FROM `{$table}` WHERE `deleted`=0 {$where} ORDER BY `{$column}`";
        $query = $this->query($sql);

        $options = "<option value=''>{$select}</option>";
        $selected = '';

        while ($q = $this->fetch($query)) {
            if ($id == $q['id']) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $options .= <<<HTML
                <option {$selected} value="{$q['id']}">{$q[$column]}</option>
HTML;
        }

        return $options;
    }

    public function show_list($table, $where='')
    {
        $table = $this->security($table);

        $sql = "SELECT * FROM `{$table}` WHERE `deleted`=0 {$where}";
        $query = $this->query($sql);
//echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function get_pagination($table, $where='') {

        if (!isset($_GET['page']) || empty($_GET['page']) || $_GET['page'] < 0)  {
            $page = 1;
        }
        else {
            $page = $this->security($_GET['page']);
            if (!ctype_digit ($page)) {
                $page = 1;
            }
        }

        if (!isset($_GET['first']) || empty($_GET['first']))  {
            $first = 1;
        }
        else {
            $first = $this->security($_GET['first']);
            if (!ctype_digit ($first)) {
                $first = 1;
            }
        }

        if (!isset($_GET['view']) || empty($_GET['view']))  {
            $view = 15;
        }
        else {
            $view = $this->security($_GET['view']);
            if (!ctype_digit ($view)) {
                $view = 15;
            }
        }

        $view = 50;//

        $firstPlus = $first + 1;
        $firstMinus = $first - 1;

        $pagePlus = $first*5+1;
        $pageMinus = $first*5-9;

        $c = 0;

        $sql = "SELECT * FROM `{$table}` WHERE `deleted`=0 {$where}";
//        echo $sql;
        $query = $this->query($sql);
        $count = $this->numRows($query);

        $pageCount = $count/$view;
        $pageCount = ceil($pageCount);

        $pagenation = '';

        $pagenationStart = <<<HTML
            <a href="?page={$pageMinus}&first={$firstMinus}">&laquo;</a>
HTML;

        $pagenationFinish = <<<HTML
            <a href="?page={$pagePlus}&first={$firstPlus}">&raquo;</a>
HTML;

        for ($i=($first-1)*5+1; $i<=$pageCount; $i++){
            if ($c == 5) {
                break;
            }

            if ($i == $page) {
                $active = 'active';
            }
            else {
                $active = '';
            }

            $pagenation .= <<<HTML
                <a class="{$active}" href="?page={$i}&first={$first}">{$i}</a>
HTML;
            $c++;
        }

        $firstMax = ceil($pageCount/5);

        if ($first == 1) {
            $pagenationStart = '';
        }
        if ($first == $firstMax){
            $pagenationFinish = '';
        }

        $pagenation = $pagenationStart.$pagenation.$pagenationFinish;

        return $pagenation;
    }

    public function show_list_for_ratings($where='', $limit='')
    {

        $sql = "SELECT CONCAT(c.name, ' ', c.surname) as `ad`, f.ad `fenn`, r.duz, r.sehv, r.vaxt, r.rating FROM `ratings` r LEFT JOIN `clients` c ON(r.user_id=c.id) LEFT JOIN `fennler` f ON(r.fenn_id=f.id) WHERE r.deleted=0 {$where} ORDER BY r.rating DESC, r.duz DESC, r.sehv ASC, r.vaxt ASC, r.id DESC {$limit}";
        $query = $this->query($sql);
//echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function  show_list_for_average_ratings ($where='', $limit='') {
        $sql = "SELECT CONCAT(c.name, ' ', c.surname) as `ad`, u.ad as `universitet`, q.ad `qrup`, a.average FROM `averages` a LEFT JOIN `clients` c ON(a.client_id=c.id) LEFT JOIN `qruplar` q ON(c.qrup_id=q.id) LEFT JOIN `uniler` u ON (q.uni_id=u.id) WHERE a.deleted=0 {$where} ORDER BY a.average DESC {$limit}";
        $query = $this->query($sql);
//echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function list_by_id($table, $id) {
        $table = $this->security($table);
        $id = $this->security($id);

        $sql = "SELECT * FROM `{$table}` WHERE `id`='{$id}'";
        $query = $this->query($sql);

        $q = $this->fetch($query);

        return $q;
    }

    public function delete($table, $id, $where='id')
    {
        $id = $this->security($id);
        $table = $this->security($table);

        $arr = array();

        $sql1 = "SELECT * FROM `{$table}` WHERE `{$where}` = '{$id}'";
        $query1 = $this->query($sql1);
        while ($q = $this->fetch($query1)) {
            array_push($arr, $q);
        }

        if ($this->numRows($query1) > 0) {
            if ($arr[0]['username'] == $_SESSION['username']) {
                $_SESSION['selectuser'] = $arr[0]['username'];
            }
            $sql = "DELETE FROM `{$table}` WHERE `{$where}` = '{$id}'";

            $query = $this->query($sql);
        } else {
            return false;
        }

        if ($query) {
            //delete images
            if (isset($arr[0]['image'], $arr[0]['images'])) {
                for ($i=0;$i<count($arr);$i++) {
                    $image = $arr[$i]['image'];
                    $imagesString = $arr[$i]['images'];

                    $images = explode('*', $imagesString);

                    unlink($_SERVER['DOCUMENT_ROOT'].$image);
                    foreach ($images as $key=>$value) {
                        unlink($_SERVER['DOCUMENT_ROOT'].$value);
                    }
                }
            }

            return true;
        }
        else {
            return false;
        }
    }

    public function deleteMany($allid, $table)
    {
        $allid = substr($allid, 0, -1);

        $array = explode(",", $allid);

        $res = true;

        foreach ($array as $value) {
            if (!$this->delete($table, $value)) {
                $res = false;
            }
        }

        return $res;
    }

    public function deleteImage($id, $table, $image) {
        $id = $this->security($id);
        $table = $this->security($table);
        $image = $this->security($image);

        $query = $this->query("SELECT `images` FROM `{$table}` WHERE `id`='{$id}'");
        $q = $this->fetch($query);
        $imagesString = $q['images'];

        $imagesArray = explode('*', $imagesString);

        foreach ($imagesArray as $key=>$value) {
            if($value==$image) {
                unset($imagesArray[$key]);
            }
        }

        $imagesNewString = implode('*', $imagesArray);

        $update = $this->query("UPDATE `{$table}` SET `images`='{$imagesNewString}' WHERE `id`='{$id}'");

        if ($update) {
            unlink($_SERVER['DOCUMENT_ROOT'].$image);
            return true;
        }
        else {
            return false;
        }
    }

    public function get_ip() {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d H:i:s',time()+14560);

        $sql = "INSERT INTO `ips` (`ip`, `date`) VALUES ('{$ipaddress}','{$date}')";
        $query = $this->query($sql);

        return $query;
    }

    public function getInterval($exam_id) {
        $sql = "SELECT COUNT(s.id) `count`, MIN(s.id) `start` FROM `exams` e LEFT JOIN `fennler` f ON(e.fenn_id=f.id) RIGHT JOIN `suallar` s ON(s.fenn_id=f.id) WHERE e.id={$exam_id} ORDER BY s.id";
        $query = $this->query($sql);
        $q = $this->fetch($query);
        $count = $q['count'];

        $countMin = $count-10;

        $result =<<<HTML
            <div class="input-group">
                <input class="form-control" type="number" min="1" max="{$countMin}" name="hardan" placeholder="hardan (min=1; max={$countMin})" required>
            </div>
            <br>
            <div class="input-group">
                <input class="form-control" type="number" min="10" max="{$count}" name="hara" placeholder="hara (min=10; max={$count})" required>
            </div>
            <br>
            <div class="input-group">
                <input class="form-control" type="number" min="10" max="{$count}" name="say" placeholder="neçə sual olsun? (min=10; max{$count})" required>
            </div>
            <br>
            <input type="hidden" name="start" value="{$q['start']}">
HTML;

        return $result;
    }

//    public function getinterval($fenn_id) {
//        $start = $this->show_list('suallar', "WHERE `fenn_id`={$fenn_id} ORDER BY `id`");
//        //$finish = $this->show_list('suallar', "WHERE `fenn_id`={$fenn_id} ORDER BY `id` DESC");
//
//        $options = <<<HTML
//                <option value="f">Bütün suallar</option>
//HTML;
//
//        $c = count($start);
//
//        $sw = -49;
//        $s = $start[0]['id'];
//
//        for ($i = 1; $i<$c; $i+=50) {
//            $f = $s + 49;
//            $sw = $sw + 50;
//            $fw = $sw + 49;
//            if ($i + 50 >= $c) {
//                $options .= <<<HTML
//                    <option value="{$s}-f">{$sw}-{$c} arası</option>
//HTML;
//            }
//            else {
//                $options .= <<<HTML
//                    <option value="{$s}-{$f}">{$sw}-{$fw} arası</option>
//HTML;
//            }
//            $s = $s + 50;
//        }
//
//        return $options;
//    }

    public function login($username, $password, $remember) {
        $username = $this->security($username);
        $password  = $this->security($password);

        $password = sha1(substr(md5($password), 3, -8));

        $sql = "SELECT * FROM `clients` WHERE `username`='{$username}' AND `password`='{$password}' AND `deleted`=0";
        $query = $this->query($sql);
//        echo $sql;

        if ($this->numRows($query) > 0) {
            $q = $this->fetch($query);
            $_SESSION['client_id'] = $q['id'];

            if ($remember == 1) {
                setcookie('client_id', $q['id'], time()+7*24*3600);
                setcookie('client_pass', $password, time()+7*24*3600);
            }

            return true;
        }
        else {
            return false;
        }
    }

    public function register($array) {
        $keys = '';
        $values = '';

        $username = $this->security($array['username']);
        $email = $this->security($array['email']);

        $sqlCheck = "SELECT * FROM `clients` WHERE (`username`='{$username}' OR `email`='{$email}') AND deleted=0";
        $queryCheck = $this->query($sqlCheck);

        if ($this->numRows($queryCheck) > 0) {
            return 1; //istifadeci var
        }

        $sqlGroupControl = "SELECT `say`-`dolu` as `bos` FROM `qruplar` WHERE `id`={$array['qrup_id']} AND `pass`='{$array['pass']}'";
        $queryGroupControl = $this->query($sqlGroupControl);
        $qGroupControl = $this->fetch($queryGroupControl);

        unset($array['pass']);

        if ($this->numRows($queryGroupControl) == 1) {
            if ($qGroupControl['bos'] == 0) {
                return 2; //qrupda bos yer yoxdur
            }
        }
        else {
            return 4; //qrup sifresi sehvdir
        }


        foreach ($array as $key => $value) {
            $value = $this->security($value);
            if ($key == 'password') {
                $value = sha1(substr(md5($value), 3, -8));
            }
            $keys .= "`" . $key . "`,";
            $values .= "'" . $value . "',";
        }

        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        $sql = "INSERT INTO `clients` ({$keys}) VALUES ({$values})";
//echo $sql;
        $query = $this->query($sql);

        if ($query) {
            return false;
        }
        else {
            return 3; //sehv
        }
    }

    public function group_minus($qrup_id) {
        $sql = "UPDATE `qruplar` SET `dolu`=`dolu`+1 WHERE `id`={$qrup_id}";
        $query = $this->query($sql);

        return $query;
    }

    public function check_user() {
        $client_id = 0;

        if (isset($_SESSION['client_id']) && !empty($_SESSION['client_id']) && ctype_digit($_SESSION['client_id'])) {
            $client_id = $_SESSION['client_id'];
        }
        else {
            if (isset($_COOKIE['client_id']) && !empty($_COOKIE['client_id']) && ctype_digit($_COOKIE['client_id']) && isset($_COOKIE['client_pass']) && !empty($_COOKIE['client_pass'])) {
                $c_id = $this->get_security($_COOKIE['client_id']);
                $c_pass = $this->get_security($_COOKIE['client_pass']);

                $sqlC = "SELECT * FROM `clients` WHERE `id`={$c_id} AND `password`='{$c_pass}' AND `deleted`=0";
                $queryC = $this->query($sqlC);

                if ($this->numRows($queryC) == 1) {
                    $qC = $this->fetch($queryC);
                    $_SESSION['client_id'] = $c_id;
                    return $qC;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }

        $sql = "SELECT * FROM `clients` WHERE `id`={$client_id} AND `deleted`=0";
        $query = $this->query($sql);

        if ($this->numRows($query) == 1) {
            $q = $this->fetch($query);
            setcookie('client_id', $q['id'], time()+7*24*3600);
            setcookie('client_pass', $q['password'], time()+7*24*3600);
            return $q;
        }
        else {
            return false;
        }
    }

    public function get_exams($c_id) {
        $sqlQrup = "SELECT * FROM `clients` WHERE `id`={$c_id} AND `deleted`=0";
        $queryQrup = $this->query($sqlQrup);
        $qQrup = $this->fetch($queryQrup);
        $qrup_id = $qQrup['qrup_id'];

        $arr = array();

        $sql = "SELECT e.id, e.fenn_id, f.ad `fenn`, e.qrup_id FROM `exams` e LEFT JOIN `fennler` f ON(e.fenn_id=f.id) WHERE e.deleted=0 AND e.qrup_id={$qrup_id} AND e.finish_date>=CURRENT_DATE ORDER BY f.ad";
        $query = $this->query($sql);

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function check_exam_for_user($c_id, $e_id) {
        $sqlQrup = "SELECT * FROM `clients` WHERE `id`={$c_id}";
        $queryQrup = $this->query($sqlQrup);
        $qQrup = $this->fetch($queryQrup);
        $qrup_id = $qQrup['qrup_id'];

        $sql = "SELECT e.fenn_id FROM `exams` e LEFT JOIN `fennler` f ON(e.fenn_id=f.id) WHERE e.deleted=0 AND e.qrup_id={$qrup_id} AND e.id={$e_id} AND e.finish_date>=CURRENT_DATE";
        $query = $this->query($sql);
//        echo $sql."<br>";
        return $this->fetch($query);
    }

}

$main = new Main();