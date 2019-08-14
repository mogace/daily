<?php 
/**
 * 类和对象学习 traits
 * @date 2019/08/13
 * @author mogace
 * 
 */
interface teacher{
	public function getClasses();
}

trait human{
	public function say1(){
		echo 'human';
	}
}

trait student{
	public function say2(){
		echo 'student';
	}
}

class hello{
	use human,student;
	public function laugh(){
		echo '!';
		$this->say1();
	}
}

$hello = new hello();
$hello->say1();
echo '<br/>';
$hello->say2();
echo '<br/>';
$hello->laugh();
 ?>