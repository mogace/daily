<?php 
/**
 * 正则表达式
 * @date 2019/08/18
 * @author mogace
 * 
 */

$str = 'this is reg
Reg
this is
regexp turtor,oh reg';
if (preg_match_all('%.*reg$%mi', $str, $arr)) {
	var_dump($arr);
} else {
	var_dump($arr);
}
 

 ?>