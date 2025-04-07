<?php
require '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$config = require 'mail_config.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

try 
{
    $mail->isSMTP();
    $mail->Host       = $config['host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['username'];
    $mail->Password   = $config['password'];
    $mail->SMTPSecure = $config['encryption'];
    $mail->Port       = $config['port'];

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($_POST['to']);
    $mail->Subject = $_POST['subject'];
    $mail->Body    = $_POST['message'];
    $mail->isHTML(true);

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) 
    {
        $mail->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
    }

    $mail->send();
    echo "<script>alert('Gửi email thành công!'); window.location.href='http://localhost/prjhrmthuan/views/index.php?EmailManagement';</script>";
} 
catch (Exception $e) 
{
    echo "<script>alert('Lỗi: {$mail->ErrorInfo}'); window.history.back();</script>";
}
