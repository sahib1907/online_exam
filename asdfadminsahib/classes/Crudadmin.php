<?php

/**
 * Created by PhpStorm.
 * User: sahib
 * Date: 25.04.2017
 * Time: 13:42
 */
class Crudadmin extends Db
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

    public function random_string()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 4; $i++) {
            $randstring .= $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

    public function add($table, $array)
    {
        $keys = '';
        $values = '';

        $table = $this->security($table);

        foreach ($array as $key => $value) {
            $value = $this->security($value);
            if ($key == 'password' && $table != 'fennler') {
                $value = sha1(substr(md5($value), 3, -8));
            }
            $keys .= "`" . $key . "`,";
            $values .= "'" . $value . "',";
        }

        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        $sql = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";

        return $this->query($sql);
    }

    public function prepare_edit($table, $id)
    {
        $id = $this->security($id);
        $table = $this->security($table);

        $sql = "SELECT * FROM `{$table}` WHERE `id`='{$id}' AND `deleted`=0";
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
                if ($key == 'password' && $table != 'fennler') {
                    if ($value == '') {
                        continue;
                    }
                    $value = sha1(substr(md5($value), 3, -8));
                }
                $updatestring .= "`{$key}`='{$value}',";
            }
            $updatestring = substr($updatestring, 0, -1);

            $sql = "UPDATE `{$table}` SET {$updatestring} WHERE `id`='{$id}' AND `deleted`=0";

            return $this->query($sql);
        } else {
            return false;
        }
    }

    public function get_data_for_select($table, $column, $id = 0, $where='')
    {
        $id = $this->security($id);
        $table = $this->security($table);
        $column = $this->security($column);

        $sql = "SELECT * FROM `{$table}` WHERE `deleted`=0 {$where} ORDER BY `{$column}`";
        $query = $this->query($sql);

        $options = '<option value="0">Select</option>';
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

        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function list_for_categories($table1, $table2, $select, $on, $where='')
    {
        //deleted=0 function cagrilanda
        $table1 = $this->security($table1);
        $table2 = $this->security($table2);

        $sql = "SELECT {$select} FROM `{$table1}` t1 LEFT JOIN `{$table2}` t2 ON({$on}) WHERE t1.deleted=0 {$where}";
        $query = $this->query($sql);
//echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function list_for_cavablar($table, $table2, $where='')
    {
        $table = $this->security($table);
        $table2 = $this->security($table2);

        //$link = c1.link

        $sql = "SELECT c1.id, c1.ad, c1.duzgun, c1.sual_id, c3.ad as `sual`, c2.ad as `fenn` FROM {$table} c1 LEFT JOIN {$table2} c2 on(c1.fenn_id=c2.id) LEFT JOIN `suallar` c3 on(c1.sual_id=c3.id) {$where}";
        $query = $this->query($sql);

        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function delete($table, $id, $where='id')
    {
        $id = $this->security($id);
        $table = $this->security($table);
        $where = $this->security($where);

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

    public function logical_deletion($table, $id, $where='id', $type='') {
        $id = $this->security($id);
        $table = $this->security($table);
        $where = $this->security($where);

        $sql = "UPDATE `{$table}` SET `deleted`=1 WHERE `{$where}`={$id}";
        $query = $this->query($sql);

        if ($query == true && $type == 'fenn') {
            $delete_suallar = $this->logical_deletion('suallar', $id, 'fenn_id');
            $delete_cavablar = $this->logical_deletion('cavablar', $id, 'fenn_id');
        }
        if ($query == true && $type == 'sual') {
            $delete_cavablar = $this->logical_deletion('cavablar', $id, 'sual_id');
        }
        if ($query == true && $type == 'qrup') {
            $delete_users = $this->logical_deletion('clients', $id, 'qrup_id');
        }

        return $query;
    }

    public function logicalDeleteMany($allid, $table, $type='')
    {
        $allid = substr($allid, 0, -1);

        $array = explode(",", $allid);

        $res = true;

        foreach ($array as $value) {
            if (!$this->logical_deletion($table, $value, 'id', $type)) {
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

    public function get_admin()
    {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM `users` WHERE `username`='{$username}'";
        $query = $this->query($sql);

        return $this->fetch($query);
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
            <a href="?module={$_GET['module']}&action=list&page={$pageMinus}&first={$firstMinus}">&laquo;</a>
HTML;

        $pagenationFinish = <<<HTML
            <a href="?module={$_GET['module']}&action=list&page={$pagePlus}&first={$firstPlus}">&raquo;</a>
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
                <a class="{$active}" href="?module={$_GET['module']}&action=list&page={$i}&first={$first}">{$i}</a>
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

    public function list_for_exams() {
        $sql = "SELECT e.id, q.ad `qrup`, f.ad `fenn`, e.start_date, e.finish_date FROM `exams` e LEFT JOIN `qruplar` q ON(e.qrup_id=q.id) LEFT JOIN `fennler` f ON(e.fenn_id=f.id) WHERE e.deleted=0";
        $query = $this->query($sql);

        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function group_minus($qrup_id) {
        $sql = "UPDATE `qruplar` SET `dolu`=`dolu`+1 WHERE `id`={$qrup_id}";
        $query = $this->query($sql);

        return $query;
    }

    public function show_list_for_ratings($where='', $limit='')
    {

        $sql = "SELECT r.id, CONCAT(c.name, ' ', c.surname) as `ad`, f.ad `fenn`, r.duz, r.sehv, r.vaxt, r.rating FROM `ratings` r LEFT JOIN `clients` c ON(r.user_id=c.id) LEFT JOIN `fennler` f ON(r.fenn_id=f.id) WHERE r.deleted=0 {$where} ORDER BY r.id DESC {$limit}";
        $query = $this->query($sql);

//        echo "<br><br><br><br><br>";
//        echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

    public function  show_list_for_average_ratings ($where='', $limit='') {
        $sql = "SELECT a.id, CONCAT(c.name, ' ', c.surname) as `ad`, u.ad as `universitet`, q.ad `qrup`, a.average FROM `averages` a LEFT JOIN `clients` c ON(a.client_id=c.id) LEFT JOIN `qruplar` q ON(c.qrup_id=q.id) LEFT JOIN `uniler` u ON (q.uni_id=u.id) WHERE a.deleted=0 {$where} ORDER BY a.id DESC {$limit}";
        $query = $this->query($sql);
//echo $sql;
        $arr = array();

        while ($q = $this->fetch($query)) {
            array_push($arr, $q);
        }

        return $arr;
    }

}

$crud = new Crudadmin();