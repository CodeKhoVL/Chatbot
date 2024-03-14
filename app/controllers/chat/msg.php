<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$message      = check_string($_POST['message']);
$href      = check_string($_POST['href']);
$time       = time();
$rows =  $zoom;
$victim = $duogxaolin->get_row(" SELECT * FROM `users` WHERE `id` = '" . $rows['victim'] . "' ");
if ($message == '') {
 $message = '<i class="fas fa-thumbs-up"></i>';
}
$duogxaolin->update("zoom", [
    'time'     => time(),
    'is_read'     => 0
], " `zoom_id` = '$zoom_id' ");
$duogxaolin->insert("messages", [
    'zoom_id'             => $zoom_id,
    'msg'                 => $message,
    'out_id'     => $auth['id'],
    'time'                => time(),
    'data'      => 'null',
    'in_id'     => $rows['victim']
]);
$return['href'] = 'chat';
$return['status'] = 'success';
$return['type']   = 'reload';
$return['id'] = $zoom_id;
$return['msg']   = 'Gửi thành công !';
die(json_encode($return));