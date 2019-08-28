<?php 
/**
 * SMTP协议
 * @date 2019/08/28
 * @author mogace
 * 
 */
// open smtp.qq.com 25
// HELO smtp.qq.com
// AUTH login
// 输入base64加密过的用户名
// 输入base64加密过的密码
// MAIL FROM: <xxx@qq.com>
// REPT TO: <xxx@qq.com>
// DATA
// this is example for smtp protocol
// .
// QUIT

var_dump(base64_encode('254033181@qq.com'));

echo '<br/>';

var_dump(base64_encode('900510...wjy'));

 ?>