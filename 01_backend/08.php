<?php 
/**
 * SMTP协议
 * @date 2019/08/28
 * @author mogace
 * 
 */
// open smtp.qq.com 25
// HELO smtp.qq.com
// AUTH LOGIN
// 输入base64加密过的用户名
// 输入base64加密过的密码
// MAIL FROM: <xxx@qq.com>
// RCPT TO: <xxx@qq.com>
// DATA
// this is example for smtp protocol
// .
// QUIT

include('smtp.class.php');
$mail = new smtpMail();

$mail->host = 'smtp.qq.com';
$mail->port = 25;
$mail->user = base64_encode('3090731559@qq.com');
$mail->pass = base64_encode('sciytclirotqdgef');

$from = '3090731559@qq.com';
$to = '731323616@qq.com';
$subject = 'hello mogace, nice to meet you!';

$mail->smtp_mail();
$result = $mail->send_mail($from, $to, $subject);

var_dump($result);
 ?>