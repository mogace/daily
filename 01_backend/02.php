<?php 
/**
 * 类和对象学习
 * @date 2019/08/13
 * @author mogace
 * 
 */
class human{
	public function say(){
		echo 'human';
	}
}

class student extends human{
	public function say(){
		parent::say();
		echo 'student';
	}
}

class teacher extends human{
	public function say(){
		echo 'teacher';
	}
}

function doprint($obj){
	if (get_class($obj) == 'human') {
		echo 'error';
	} else {
		$obj->say();
	}
}

//doprint(new student);
echo '<br/>';
//doprint(new human);
$dir = new DirectoryIterator(dirname(__FILE__));
var_dump($dir);


 ?>