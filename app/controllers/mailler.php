<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendCSM($subject, $body, $bcc)
{
    global $duogxaolin;
    $mail = new PHPMailer(true);
    try {
        // Cấu hình thông tin SMTP
        $mail->isSMTP();
        $mail->Host = $duogxaolin->site('web_mail');
        $mail->SMTPAuth = true;
        $mail->Username = $duogxaolin->site('email');
        $mail->Password = $duogxaolin->site('pass_email');
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Cấu hình thông tin email
        $mail->setFrom($duogxaolin->site('email'));
        
        // Thêm người nhận BCC
        foreach ($bcc as $bcc_email) {
            $mail->addBCC($bcc_email);
        }

        $mail->addReplyTo($duogxaolin->site('email'));
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->CharSet = 'UTF-8';

        // Gửi email
        $mail->send();
        $return['status'] = true;
        $return['msg']   = 'Gửi thành công';
    } catch (Exception $e) {
        $return['status'] = true;
        $return['msg']   = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return (json_encode($return));
}
/*
function content_mail($content,$id,$otp = 000000){
    global $duogxaolin;
    $get = $duogxaolin->get_row("SELECT * FROM `users` WHERE `id` = '$id' ");
    $content = str_replace("{username}", $get['username'], $content);
    $content = str_replace("{username}", $get['username'], $content);
    $content = str_replace("{email}", $get['email'], $content);
    $content = str_replace("{phone}", $get['phone'], $content);
    $content = str_replace("{wallet_num}", $get['wallet_num'], $content);
    $content = str_replace("{money}", $get['money'], $content);
    $content = str_replace("{createdate}", $get['createdate'], $content);
    $content = str_replace("{banned}", $get['banned'], $content);
    $content = str_replace("{fullname}", $get['fullname'], $content);
    $content = str_replace("{otp}", $otp, $content);
    return $content;
}

function sentmail($type,$id,$email,$otp = 000000){
    global $duogxaolin;
    $form = $duogxaolin->site($type.'_subject');
    $subject =$duogxaolin->site($type.'_subject');
    $content = $duogxaolin->site($type);
    $content = content_mail($content,$id,$otp);


    $bcc = array(
        'bcc1@example.com' => 'BCC Recipient 1',
        'bcc2@example.com' => 'BCC Recipient 2',
        // Thêm các địa chỉ email BCC khác nếu cần
    );
    
    // Gọi hàm để gửi email
    sendCSM($subject, $content, $bcc);
} */