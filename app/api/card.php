<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
if (isset($_GET['status'])) {
    $status = check_string($_GET['status']); // trạng thái
    $mess    = check_string($_GET['message']);
    $request_id = check_string($_GET['request_id']); // request id đưa lên
    $declared_value = check_string($_GET['declared_value']); // mệnh giá đưa lên
    $value = check_string($_GET['value']); // mệnh giá thực
    $amount = check_string($_GET['amount']); // thực nhận của thẻ
    $code = check_string($_GET['code']);
    $serial = check_string($_GET['serial']);
} else if (isset($_POST['status'])) {
    $status = check_string($_POST['status']); // trạng thái
    $mess    = check_string($_POST['message']);
    $request_id = check_string($_POST['request_id']); // request id đưa lên
    $declared_value = check_string($_POST['declared_value']); // mệnh giá đưa lên
    $value = check_string($_POST['value']); // mệnh giá thực
    $amount = check_string($_POST['amount']); // thực nhận của thẻ
    $code = check_string($_POST['code']);
    $serial = check_string($_POST['serial']);
} else {

    $status = check_string($_POST['status']); // trạng thái
    $mess    = check_string($_POST['message']);
    $request_id = check_string($_POST['request_id']); // request id đưa lên
    $declared_value = check_string($_POST['declared_value']); // mệnh giá đưa lên
    $value = check_string($_POST['value']); // mệnh giá thực
    $amount = check_string($_POST['amount']); // thực nhận của thẻ
    $code = check_string($_POST['code']);
    $serial = check_string($_POST['serial']);
}
$time = time();
$filePath = 'response.txt';
$file = fopen($filePath, 'a');
fwrite($file, json_encode($pay) . PHP_EOL);
fclose($file);
$row = $duogxaolin->get_row("SELECT * FROM `chargings_card` WHERE `code` = '$request_id' ");
$getUser = $duogxaolin->get_row("SELECT * FROM `users` WHERE `id` = '" . $row['user_id'] . "' ");
$telco = $row['loaithe'];
if (!$row) {
    exit('Request ID không tồn tại');
}
if ($row['trangthai'] != 'xuly') {
    exit('Thẻ này đã được xử lý rồi');
}

if ($value == 0 or $value == '0') {
    $thucnhan = 0;
    $duogxaolin->update("chargings_card", [
        'amount'    => 0,
        'trangthai' => 'thatbai',
        'menhgiathuc' => $value,
        'ghichu'    => 'Thẻ không hợp lệ hoặc đã được sử dụng.',
        'capnhat'   => gettime(),
        'thucnhan'  => 0,
    ], " `code` = '$request_id' ");
    exit('Thẻ không hợp lệ !');
}
if ($status == 3) {
    $thucnhan = 0;
    $duogxaolin->update("chargings_card", [
        'amount'    => 0,
        'trangthai' => 'thatbai',
        'menhgiathuc' => $value,
        'ghichu'    => 'Thẻ không hợp lệ hoặc đã được sử dụng.',
        'capnhat'   => gettime(),
        'thucnhan'  => 0,
    ], " `code` = '$request_id' ");
    exit('Thẻ không hợp lệ !');
}
if ($value != 0) {
    //sai mệnh giá
    if ($status == 2) {
        if ($value < $declared_value) {
            $menhgia = $value;
        } else {
            $menhgia = $value;
        }
        $ck = $duogxaolin->get_row(" SELECT * FROM `chargings_fees` WHERE `telco` = '" . $row['loaithe'] . "' AND `value` = '$value' AND `group`= '1'  ");
        $ck = is_array($ck) ? $ck['fees'] : false;
        $thucnhan = $menhgia - $menhgia * $ck / 100;
        $thucnhan = $thucnhan * 70 / 100;
        $duogxaolin->update("chargings_card", [
            'trangthai' => 'menhgia',
            'thucnhan'  => $thucnhan,
            'fees'      => $ck,
            'fees2'      => '30',
            'amount'    => $amount,
            'menhgiathuc' => $value,
            'ghichu'    => 'Sai mệnh giá -30%, mệnh giá thực ' . format_cash($value),
            'capnhat'   => gettime()
        ], " `code` = '$request_id' ");
        $duogxaolin->cong("users", "money", $thucnhan, " `id` = '" . $row['user_id'] . "' ");
        $duogxaolin->cong("users", "total_money", $thucnhan, " `id` = '" . $row['user_id'] . "' ");
        /* CẬP NHẬT DÒNG TIỀN */
        $duogxaolin->insert("history", array(
            'magd'          => $serial,
            'sotientruoc'   => $getUser['money'],
            'sotienthaydoi' => $thucnhan,
            'sotiensau'     => $getUser['money'] + $thucnhan,
            'thoigian'      => gettime(),
            'noidung'       => 'Đổi thẻ seri (' . $serial . ')',
            'user_id'      => $getUser['id']
        ));
        exit('Thẻ sai mệnh giá !');
    } else {   //hết sai mệnh giá
        $duogxaolin->update("chargings_card", [
            'amount'    => $amount,
            'trangthai' => 'hoantat',
            'menhgiathuc' => $value,
            'capnhat'   => gettime()
        ], " `code` = '$request_id' ");
        /**
         * CỘNG TIỀN CHO USER
         */
        $thucnhan = $row['thucnhan'];
        $duogxaolin->cong("users", "money", $row['thucnhan'], " `id` = '" . $row['user_id'] . "' ");
        $duogxaolin->cong("users", "total_money", $row['thucnhan'], " `id` = '" . $row['user_id'] . "' ");
        $duogxaolin->insert("history", array(
            'magd'          => $serial,
            'sotientruoc'   => $getUser['money'],
            'sotienthaydoi' => $row['thucnhan'],
            'sotiensau'     => $getUser['money'] + $row['thucnhan'],
            'thoigian'      => gettime(),
            'noidung'       => 'Đổi thẻ seri (' . $serial . ')',
            'user_id'      => $getUser['id']
        ));
        exit('Thẻ đúng !');
    }
}
