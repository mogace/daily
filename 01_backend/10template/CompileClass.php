<?php 

class ComplieClass{
	// 待编译的文件，需要替换的文本，编译后的文件
	private $template, $content, $comfile;

	private $left = "{";
	private $right = "}";
	private $value = [];

	public function __construct(){

	}

	public function compile($source, $destFile){
		file_put_contents($destFile, file_get_contents($source));
	}

	public function c_var(){
		$pattern = "#\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f_\xff]*)\}#";
		if (strpos($this->content, '{$') !== false) {
			$this->content = preg_replace($pattern, "<?php php echo \$this->value['\\1']; ?>", $this->content);
		}
	}
}

 ?>