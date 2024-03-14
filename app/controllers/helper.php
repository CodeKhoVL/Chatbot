<?php
error_reporting(1);
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once __DIR__ . '/RSACrypt.php';
$rsa = new RSACrypt();
function encryptData($data)
{
    global $rsa;
    $rsa->setPrivateKey(__DIR__ . '/clientPrivate.pem');
    $rsa->setPublicKey(__DIR__ . '/serverPublic.pem');
    return $rsa->encryptWithPublicKey($data);
}
function decodecryptData($data)
{
    global $rsa;
    $rsa->setPrivateKey(__DIR__ . '/serverPrivate.pem');
    $rsa->setPublicKey(__DIR__ . '/clientPublic.pem');
    return $rsa->decryptWithPrivateKey($data);
}
$expiration = time() + (30 * 24 * 60 * 60); // Thời gian hết hạn là 30 ngày
session_start();
$domain = $_SERVER['HTTP_HOST'];
$domain = trim($domain, "www.");
class System_Core
{

    public function connect_db()
    {
        global $connect;
        $conn = mysqli_connect($connect['hostname'], $connect['username'], $connect['password'], $connect['database']) or die("Bảo trì nâng cấp server");
        mysqli_select_db($conn, $connect['database']) or die("Bảo trì nâng cấp server");
        $conn->set_charset("utf8");
        return $conn;
    }

    public function __construct()
    {
        $this->connect_db();
    }
    public function home_url()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domain   = $_SERVER['HTTP_HOST'];
        return $protocol . $domain;
    }

    public function home_uri()
    {
        $domain = $_SERVER['REQUEST_URI'];
        return $domain;
    }
    public function to_slug($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
    public function auth()
    {
        $result = mysqli_query($this->connect_db(), "SELECT * FROM `users`  WHERE username = '" . $_SESSION['username'] . "' ");
        $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $row;
    }
    public function gets($table, $where)
    {
        $result = mysqli_query($this->connect_db(), "SELECT * FROM `$table`  WHERE $where ");
        $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $row;
    }
    public function anti_text($text)
    {
        $text = html_entity_decode(trim($text), ENT_QUOTES, 'UTF-8');
        //$text=str_replace(" ","-", $text);
        $text = str_replace("--", "", $text);
        $text = str_replace("@", "", $text);
        $text = str_replace("/", "", $text);
        $text = str_replace("\\", "", $text);
        $text = str_replace(":", "", $text);
        $text = str_replace("\"", "", $text);
        $text = str_replace("'", "", $text);
        $text = str_replace("<", "", $text);
        $text = str_replace(">", "", $text);
        $text = str_replace(",", "", $text);
        $text = str_replace("?", "", $text);
        $text = str_replace(";", "", $text);
        $text = str_replace(".", "", $text);
        $text = str_replace("[", "", $text);
        $text = str_replace("]", "", $text);
        $text = str_replace("(", "", $text);
        $text = str_replace(")", "", $text);
        $text = str_replace("́", "", $text);
        $text = str_replace("̀", "", $text);
        $text = str_replace("̃", "", $text);
        $text = str_replace("̣", "", $text);
        $text = str_replace("̉", "", $text);
        $text = str_replace("*", "", $text);
        $text = str_replace("!", "", $text);
        //$text=str_replace("$","-",$text);
        //$text=str_replace("&","-and-",$text);
        $text = str_replace("%", "", $text);
        $text = str_replace("#", "", $text);
        $text = str_replace("^", "", $text);
        $text = str_replace("=", "", $text);
        $text = str_replace("+", "", $text);
        $text = str_replace("~", "", $text);
        $text = str_replace("`", "", $text);
        //$text=str_replace("--","-",$text);
        $text = strtolower($text);
        return $text;
    }
    public function upload_imgur($images)
    {
        $file     = file_get_contents($images);
        $dataPost = array(
            'image' => base64_encode($file)
        );
        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        $header[] = 'Authorization: Client-ID d32a28252795ab8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPost);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function get_blogs($slug)
    {
        $result = mysqli_query($this->connect_db(), "SELECT * FROM blogs  WHERE slug ='$slug'");
        $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $row;
    }
    function site($data)
    {
        $this->connect_db();
        $row = $this->connect_db()->query("SELECT * FROM `options` WHERE `name` = '$data' ")->fetch_array();
        return $row['value'];
    }
    function query($sql)
    {
        $row = $this->connect_db()->query($sql);
        return $row;
    }
    function cong($table, $data, $sotien, $where)
    {
        $row = $this->connect_db()->query("UPDATE `$table` SET `$data` = `$data` + '$sotien' WHERE $where ");
        return $row;
    }
    function tru($table, $data, $sotien, $where)
    {
        $row = $this->connect_db()->query("UPDATE `$table` SET `$data` = `$data` - '$sotien' WHERE $where ");
        return $row;
    }
    function insert($table, $data)
    {
        $field_list = '';
        $value_list = '';
        foreach ($data as $key => $value) {
            $field_list .= ",$key";
            $value_list .= ",'" . mysqli_real_escape_string($this->connect_db(), $value) . "'";
        }
        $sql = 'INSERT INTO ' . $table . '(' . trim($field_list, ',') . ') VALUES (' . trim($value_list, ',') . ')';

        return mysqli_query($this->connect_db(), $sql);
    }
    function update($table, $data, $where)
    {
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->connect_db(), $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where;
        return mysqli_query($this->connect_db(), $sql);
    }
    function remove($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";
        return mysqli_query($this->connect_db(), $sql);
    }
    function update_value($table, $data, $where, $value1)
    {
        $sql = '';
        foreach ($data as $key => $value) {
            $sql .= "$key = '" . mysqli_real_escape_string($this->connect_db(), $value) . "',";
        }
        $sql = 'UPDATE ' . $table . ' SET ' . trim($sql, ',') . ' WHERE ' . $where . ' LIMIT ' . $value1;
        return mysqli_query($this->connect_db(), $sql);
    }
    function get_list($sql)
    {
        $result = mysqli_query($this->connect_db(), $sql);
        if (!$result) {
            die('Lỗi? Help DuogXaoLin');
        }
        $return = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $return[] = $row;
        }
        mysqli_free_result($result);
        return $return;
    }
    function get_row($sql)
    {
        $result = mysqli_query($this->connect_db(), $sql);
        if (!$result) {
            die('Lỗi? Help DuogXaoLin');
        }
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        if ($row) {
            return $row;
        }
        return false;
    }
    function num_rows($sql)
    {
        $result = mysqli_query($this->connect_db(), $sql);
        if (!$result) {
            die('Lỗi? Help DuogXaoLin');
        }
        $row = mysqli_num_rows($result);
        mysqli_free_result($result);
        if ($row) {
            return $row;
        }
        return false;
    }
}
