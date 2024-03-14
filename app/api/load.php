<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
    if (empty($_SESSION['username'])) {
        $return['error'] = 1;
        $return['msg']   = 'Vui lòng đăng nhập ! ';
      die('');
    }
    $count = $duogxaolin->num_rows(" SELECT * FROM `zoom` WHERE  `is_read` = 0 AND `user_id` = '" . $auth['id'] . "' ");
if($count == ''){
   $counts = '0'; 
}else if($count == null){
    $counts = '0'; 
}else if($count == 'null'){
    $counts = '0'; 
}else if($count <= 0){
    $counts = '0'; 
}else{
    $counts = $count;
}
   echo($counts);