<?php 

class Template{
	private $arrayConfig = [
		'suffix' => '.m',   // 设置模板文件后缀
		'templateDir' => 'template/', // 设置模板所在文件夹
		'compiledir' => 'cache', // 设置编译后存放的目录
		'cache_html' => false, // 是否编译为html文件
		'suffix_cache' => '.html', // 设置编译文件的后缀
		'cache_time' => 7200 // 多长时间自动更新，单位秒
	];

	public $file;
	static private $instance = null;
	private $value = [];

	public function __construct($arrayConfig = []){
		$this->arrayConfig = $arrayConfig + $this->arrayConfig;
	}

	// 取得模板引擎实例
	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new Template();
		}

		return self::$instance;
	}
	// 设置末班引擎
	public function setCOnfig($key, $value = null){
		if (is_array($key)) {
			$this->arrayConfig = $key + $this->arrayConfig;
		} else {
			$this->arrayConfig[$key] = $value;
		}
	}
	// 获取配置
	public function getConfig($key = null){
		if ($key) {
			return $this->arrayConfig[$key];
		} else {
			return $this->arrayConfig;
		}
	}
	// 注入单个变量
	public function assign($key, $vlaue){
		$this->value[$key]  = $value;
	}
	// 注入数组变量
	public function assignArray($array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$this->value[$key] = $vlaue;
			}
		}
	}
	// 注入变量
	public function show($file){
		$this->file = $file;
		if (!is_file($this->path())) {
			exit('找不到对应的模板');
		}

		$compileFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.php';
		
		if (!is_file($compileFile)) {
			mkdir($this->arrayConfig['compiledir']);
			
			include 'CompileClass.php';
			$compile = new ComplieClass();

			$compile->compile($this->path(), $compileFile);

		} 

		readfile($compileFile);
	}

	// 获取路径
	public function path(){
		return $this->arrayConfig['templateDir'].$this->file.$this->arrayConfig['suffix'];
	}
}


 ?>