<?php 
/**
 * 对象的设计模式
 * @date 2019/08/17
 * @author mogace
 * 
 */

/**
 * 五大原则
 * 1. 单一职责
 * 2. 接口隔离
 * 3. 开发-关闭
 * 4. 替换
 * 6. 依赖倒置
 *
 *
 *
 */
// 面向对象留言本
class message{ // 留言实体类
	public $name;
	public $email;
	public $content;

	public function __set($name, $value){
		$this->$name = $value;	
	}

	public function __get($name){
		if (!isset($this->$name)) {
			return $this->$name = null;
		}
	}
}

class gbookModel{ // 留言本模型

	private $bookPath;
	private $data;
	public function setBookPath($bookPath){
		$this->bookPath = $bookPath;
	}

	public function getBookPath(){
		echo $this->bookPath;
	}

	public function open(){

	}

	public function close(){

	}

	public function read(){
		return file_get_contents($this->bookPath);
	}

	public function write($data){
		$this->data = self::safe($data)->name.self::safe($data)->email;
		return file_put_contents($this->bookPath, $this->data);
	}

	public static function safe($data){
		$reflect = new ReflectionObject($data);
		$props = $reflect->getProperties();

		$messagebox = new stdClass();
		foreach ($props as  $prop) {
			$ivar = $prop->getName();
			$messagebox->$ivar = trim($prop->getValue($data));
		}
		return $messagebox;
	}
}
class leaveModel{ // 业务逻辑处理
	public function write(gbookModel $gb, $data){
		$book = $gb->getBookPath();
		$gb->write($data);
	}
}

class authorControl{ // 前端控制器
	public function message(leaveModel $l, gbookModel $g, message $data){
		$l->write($g, $data);
	}

	public function view(gbookModel $g){
		return $g->read();
	}

	public function delete(gbookModel $g){
		//$g->delete();
		echo self::view($g);
	}
}


$message = new message;
$message->name = 'phper';
$message->email = 'php@php.net';

$gb = new authorControl;
$book = new gbookModel;
$book->setBookPath('./03.txt');
$pen = new  leaveModel;
$gb->message($pen, $book, $message);
echo $gb->view($book);

$gb->delete($book);

 ?>