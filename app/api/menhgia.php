<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$loaithe = check_string($_POST['loaithe']);

?>
<?php foreach ($duogxaolin->get_list(" SELECT * FROM `chargings_fees` WHERE `telco` = '$loaithe'") as $row) { ?>
    <option value="<?= $row['value'] ?>">
       Thẻ <?=format_cash($row['value'])?> đ - Nhận <?=format_cash($row['value'] - ($row['value'] * $row['fees'] /100))?> đ - CK: <?=$row['fees']?> %
    </option>
<?php } ?>