<?php 
ini_set('date.timezone','Asia/Shanghai');

include 'Template.php';

$template = new Template(['php_turn' => true, 'debug' => true]);

$template->assign('data', 'hello world');
$template->assign('person', 'cafeCAT');
$template->assign('pai', 3.14);

$arr = [1, 2, 3, 4, 'haha', 6];
$template->assign('b', $arr);
$template->show('member');

 ?>