<?php
require_once('config.php');
$json_data = file_get_contents("https://script.google.com/macros/s/AKfycbw6Yz5IgSfL-DQyaDpg0fTh5H4Ic9vJNoJAEOQRz3oM9Azy2klD0F-_Ul28pAvMYUVIoQ/exec");
$data = json_decode($json_data, true);
$found_data = [];
foreach ($data as $item) {
    $found_data[] = $item;
}
//print_r($found_data);
foreach ($found_data as $row) {
    $key = xss($row['Keys']);
    $data = ($row['Data']);
    $code = xss($row['ID']);

    $rows = $duogxaolin->get_row("SELECT * FROM `data` WHERE `code` = '$code' ");

    if (!$rows) {
        $create = $duogxaolin->insert("data", [
            'code' => $code,
            'keyword' => $key,
            'content' => $data,
            'time' => time()
        ]);
        
        if ($create) {
            echo $key . "<br>";
            echo $data . "<br>";
            echo $code . "<br>";
        }
    } else {
        $create = $duogxaolin->update("data", [
            'keyword' => $key,
            'content' => $data,
            'time' => time()
        ], " `code` = '$code' ");
    }
}
