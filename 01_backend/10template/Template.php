<?php 

class Template{
	private $arrayConfig = [
		'suffix' => '.m',   // 设置模板文件后缀
		'templateDir' => 'template/', // 设置模板所在文件夹
		'compiledir' => 'cache', // 设置编译后存放的目录
		'cache_html' => false, // 是否编译为html文件
		'suffix_cache' => '.html', // 设置编译文件的后缀
		'cache_time' => 7200, // 多长时间自动更新，单位秒
		'php_turn' => true, // 是否支持原生代码
		'cache_control' => 'control.dat',
		'debug' => false
	];

	public $file; // 模板文件名 不带路径
	static private $instance = null; 
	private $value = []; // 值栈
	private $compileTool; // 编辑器
	public $dubug = []; // 调试信息
	private $controlData = [];

	public function __construct($arrayConfig = []){
		$this->debug['begin'] = microtime(true);
		$this->arrayConfig = $arrayConfig + $this->arrayConfig;
		$this->getPath();

		if (!is_dir($this->arrayConfig['templateDir'])) {
			exit('template dir is not found');
		}

		if (!is_dir($this->arrayConfig['compiledir'])) {
			mkdir($this->arrayConfig['compiledir'], 0770, true);
		}
		
		include "CompileClass.php";
	}

	// 获取路径
	public function getPath(){
		$this->arrayConfig['templateDir'] = str_replace('\\', '/', realpath($this->arrayConfig['templateDir'])).'/';
		$this->arrayConfig['compiledir'] = str_replace('\\', '/', realpath($this->arrayConfig['compiledir'])).'/';
	}

	// 取得模板引擎实例
	public static function getInstance(){
		if (is_null(self::$instance)) {
			self::$instance = new Template();
		}

		return self::$instance;
	}

	// 设置模班引擎
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
	public function assign($key, $value){
		$this->value[$key]  = $value;
	}

	// 注入数组变量
	public function assignArray($array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				$this->value[$key] = $value;
			}
		}
	}

	// 获取路径
	public function path(){
		return $this->arrayConfig['templateDir'].$this->file.$this->arrayConfig['suffix'];
	}

	// 判断是否开启了缓存
	public function needCache(){
		return $this->arrayConfig['cache_html'];
	}
	
	// 是否重新生成静态文件
	public function reCache($file){
		$flag = false;
		$cacheFile = $this->arrayConfig['compiledir'].md5($file).'.html';
		if ($this->arrayConfig['cache_html'] === true) { // 是否需要缓存
			$timeFlag = (time() - @filemtime($cacheFile) < $this->arrayConfig['cache_time']) ? true : false;
			if (is_file($cacheFile) && filesize($cacheFile) > 1 && $timeFlag) { //缓存存在未过期
				$flag = true;
			} else {
				$flag = false;
			}
		}

		return $flag;
	}

	// 显示模板
	public function show($file){
		$this->file = $file;
		if (!is_file($this->path())) {
			exit('找不到对应的模板');
		}

		$compileFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.php';
		$cacheFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.html';

		if ($this->reCache($file) === false) {
			$this->debug['cached'] = 'false';
			$this->compileTool = new ComplieClass($this->path(), $compileFile, $this->arrayConfig);
			if ($this->needCache()) {
				ob_start();
			}

			extract($this->value, EXTR_OVERWRITE);

			if (!is_file($compileFile) || filemtime($compileFile) < filemtime($this->path())) {
				$this->compileTool->vars = $this->value;
				$this->compileTool->compile();
			}
			include $compileFile;

			if ($this->needCache()) {
				$message = ob_get_contents();
				file_put_contents($cacheFile, $message);
			}
		} else {	
			readfile($compileFile);
			$this->debug['cached'] = 'true';
		}

		$this->debug['count'] ='"'.count($this->value).'"';
		$this->debug['speed'] = microtime(true) - $this->debug['begin'];


		$this->debug_info();
	}

	public function debug_info(){
		if ($this->arrayConfig['debug'] === true) {
			echo PHP_EOL,'------',PHP_EOL, "<br/>";
			echo '程序运行日期:'.date('Y-m-d h:i:s'), PHP_EOL, "<br/>";
			echo '模板解析耗时:'.$this->debug['speed'].'秒', PHP_EOL, "<br/>";
			echo '模板包含标签数目:'.$this->debug['count'], PHP_EOL, "<br/>";
			echo '是否使用静态缓存:'.$this->debug['cached'], PHP_EOL, "<br/>";
		}
	}

	public function clean($path){
		if ($path == null) {
			$path = $this->arrayConfig['compiledir'];
			$path = glob($path.'*'.$this->arrayConfig['suffix_cache']);
		} else {
			$path = $this->arrayConfig['compiledir'].md5($path).'.html';
		}

        foreach ((array)$path as $key => $value) {
            unlink($value);
        }
	}
}


 ?>