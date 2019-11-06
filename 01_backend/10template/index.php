<?php 
include 'Template.php';

$tempalte = new Template(['php_turn' => true, 'debug' => true]);

$tempalte->assign('data', 'hello world');
$template->assign('person', 'cafeCAT');
$tempalte->assign('pai', 3.14);

$arr = [1, 2, 3, 4, 'haha', 6];
$template->assign('b', $arr);
$tempalte->show('member');

 ?>