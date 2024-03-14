<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
if (!$_SESSION['username']) {
    $return['status'] = 'error';
    $return['msg']   = 'Vui Lòng Đăng Nhập Để Thực Hiện Thao Tác ! ';
    die(json_encode($return));
}
if (isset($_POST['shop'])) {
    $seller      = check_string($_POST['shop']);
    $row =  $duogxaolin->get_row("SELECT * FROM `shop` WHERE  `id` = '$seller'");
    if (!$row) {
        $return['status'] = 'error';
        $return['msg']   = 'Không thể tạo box!';
        die(json_encode($return));
    }
    $rows = $duogxaolin->get_row("SELECT * FROM `users` WHERE  `id` = '" . $row['user_id'] . "'");
    if ($auth['username'] == $rows['username']) {
        $return['status'] = 'error';
        $return['msg']   = 'Không thể tạo box với chính mình !';
        die(json_encode($return));
    }
    $nums = $duogxaolin->get_row(" SELECT * FROM `zoom` WHERE `victim` = '" . $auth['id'] . "' AND `user_id` = '" . $rows['id'] . "' ");
    if (!$nums) {
        $zoom_id = $auth['id'] . random(time(), 7) . $rows['id'];
        $duogxaolin->insert("zoom", [
            'victim'             => $rows['id'],
            'user_id'           => $auth['id'],
            'zoom_id'            => $zoom_id,
            'time'                => time(),
            'is_read'             => 0
        ]);
        $duogxaolin->insert("zoom", [
            'victim'             => $auth['id'],
            'user_id'           => $rows['id'],
            'zoom_id'            => $zoom_id,
            'time'                => time(),
            'is_read'             => 0
        ]);
    } else {
        $zoom_id = $nums['zoom_id'];
    }

    $message = 'Tôi cần hỗ trợ';
    $duogxaolin->insert("messages", [
        'zoom_id'             => $zoom_id,
        'msg'                 => $message,
        'out_id'     => $auth['id'],
        'in_id'     => $rows['id'],
        'time'                => time(),
        'data'  => 'null'
    ]);
    $return['status'] = 'success';
    $return['href'] = $duogxaolin->home_url() . '/chat/' . $zoom_id;
    $return['msg']   = 'Tạo thành công !';
    die(json_encode($return));
}
