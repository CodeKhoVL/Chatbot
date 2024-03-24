<?php
require __DIR__ . '/vendor/autoload.php';

$connect = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'hou_chatbox'
);

require_once($_SERVER['DOCUMENT_ROOT'].'/app/controllers/helper.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/controllers/libs.php');
?>