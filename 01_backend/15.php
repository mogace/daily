<?php 
require '15phpqrcode/phpqrcode.php';

$data = 'http://www.baidu.com';
$level = 'L';// 纠错级别：L、M、Q、H
$size =4;// 点的大小：1到10,用于手机端4就可以了

ob_start();
QRcode::png($data, false, $level, $size, 2);
$aa = ob_get_contents();
$imageString = base64_encode(ob_get_contents());
ob_end_clean();
 
var_dump("data:image/jpg;base64,".$imageString);
?>