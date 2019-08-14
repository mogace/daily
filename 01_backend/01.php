<?php 
/**
 * 类和对象学习
 * @date 2019/08/12
 * @author mogace
 * 
 */
class human{
	public $name,$gender,$age;
	public function say(){
		echo $this->name.' '.$this->gender;
	}
}

$student = new human();
$student->name = 'zhangsan';
$student->gender = 'male';
$abc = file_get_contents('student.txt');
$aa = unserialize($abc);

//var_dump($aa);

class family{
	public $people,$location;
	public function __construct($p, $loc){
		$this->people = $p;
		$this->location = $loc;
	}
}

$family = new family($student, 'peking');
//echo serialize($family);
echo '<br />';

$array = ['name' => 'zhangsan', 'gender' => 'male'];
//echo serialize($array);

class account{
	private $user = 1, $pwd = 2;
	public function __set($name, $value){
		return $this->$name = $value;
	}

	public function __get($name){
		return $this->$name;
	}
}

$account = new account();
echo $account->user;
$account->name = 'zhangsan';
echo $account->name.'<br/>';
echo $account->big;


 ?>