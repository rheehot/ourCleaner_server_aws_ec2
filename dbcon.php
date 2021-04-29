<?php

//echo "<br>\ndbcon.php 파일 시작 구간<br>\n";

error_reporting(E_ALL); 
ini_set('display_errors',1);

$host = 'ec2-52-79-179-66.ap-northeast-2.compute.amazonaws.com';
$username = 'aileen'; # MySQL 계정 아이디
$password = '!Nhskskp30415'; # MySQL 계정 패스워드
$dbname = 'ourCleaner'; # DATABASE 이름



$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {

    $con = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8",$username, $password);


} catch(PDOException $e) {

    die("Failed to connect to the database: " . $e->getMessage());

}


$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {


    function undo_magic_quotes_gpc(&$array) {

        //echo "=====================<br>";
        //echo "undo_magic_quotes_gpc array : ";
        //var_dump( $array );
        //echo "<br>=====================<br>";

        foreach($array as &$value) {

            if(is_array($value)) {

                undo_magic_quotes_gpc($value);
                //echo "is_array(value) 참 인 경우,";

            }
            else {

                $value = stripslashes($value);
                //echo "is_array(value) 거짓 인 경우,";

            }

        }

    }

    undo_magic_quotes_gpc($_POST);
    undo_magic_quotes_gpc($_GET);
    undo_magic_quotes_gpc($_COOKIE);

 }
 header('Content-Type: text/html; charset=utf-8');
#session_start();

//echo "<br>\ndbcon.php 파일 종료 구간<br>\n";

?>
