<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
if (!$_SESSION['username']) {
    $return['status'] = 'error';
    $return['msg']   = 'Vui Lòng Đăng Nhập Để Thực Hiện Thao Tác ! ';
    die(json_encode($return));
}
$zoom_id = xss($_POST['zoom_id']);
$type = xss($_POST['type']);
$zoom = $duogxaolin->get_row("SELECT * FROM `zoom` WHERE `zoom_id` = '$zoom_id' AND `user_id` = '" . $auth['id'] . "' ");
if (!$zoom) {
    $return['status'] = 'error';
    $return['msg']   = 'Không tìm thấy đoạn chat ! ';
    die(json_encode($return));
}
 if ($type == 'delete') {
    $id_del      = check_string($_POST['id_mess']);
    $c_del = $duogxaolin->get_row(" SELECT * FROM `messages` WHERE `zoom_id` = '$zoom_id' AND `id` = '$id_del' AND `out_id` = '" . $auth['id'] . "'");
    if ($c_del) {
        $duogxaolin->update("messages", [
            'type'     => 0
        ], " `id` = '$id_del' ");
        $duogxaolin->update("zoom", [
            'time'     => time(),
        ], " `zoom_id` = '$zoom_id' ");
        $return['status'] = 'success';
        $return['href'] = 'chat';
        $return['id'] = $zoom_id;
        $return['type']   = 'deload';
        $return['msg']   = 'Xoá thành công !!';
        die(json_encode($return));
    }else{
        $return['status'] = 'error';
        $return['msg']   = 'Có lỗi xảy ra ! Không thể xoá ! ';
        die(json_encode($return));
    
    }
} else if($type == 'msg') {
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
$return['msg']   = 'Gửi Thành Công';
die(json_encode($return));
}
