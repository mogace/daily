<?php 
/**
 * CURL学习
 * @date 2019/08/22
 * @author mogace
 * 
 */
// GET
/* $ch = curl_init();

 curl_setopt($ch, CURLOPT_URL, 'http://www.baidu.com');
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_HEADER, 1);

 $output = curl_exec($ch);
 curl_close($ch);
 echo $output;*/

// POST
$postdata = ['name' => 'zhangsan', 'email' => '1@qq.com'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.daily.com/06.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

$output = curl_exec($ch);
curl_close($ch);
echo $output;


 
 ?>