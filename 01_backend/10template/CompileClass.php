<?php 

class ComplieClass{
	// 待编译的文件，需要替换的文本，编译后的文件
	private $template, $content, $comfile;

	private $left = "{";
	private $right = "}";
	private $value = [];
	private $phpTurn;
	private $T_P = [];
	private $T_R = [];

	public function __construct($template, $compileFile, $config){
		$this->template = $template;
		$this->comfile = $compileFile;
		$this->content = file_get_contents($template);

		if ($config['php_turn'] === false) {
			$this->T_P[] = "#<\? (=|php |)(.+?)\?>#is";
			$this->T_R[] = "&lt;? \\1\\2 &gt;";
		}

		$this->T_P[] = "#\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f_\xff]*)\}#";
		$this->T_P[] = "#\{(loop|foreach) \\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f_\xff]*)\}#i";
		$this->T_P[] = "#\{\/(loop|foreach|if)\}#i";
		$this->T_P[] = "#\{([K|V])\}#";
		$this->T_P[] = "#\{if (.* ?)\}#i";
		$this->T_P[] = "#\{(else if|elseif) (.* ?)\}#i";
		$this->T_P[] = "#\{else\}#i";
		$this->T_P[] = "#\{(\#|\*) (.* ?)(\#|\*)\}#";
		
		$this->T_R[] = "<?php echo \$this->value['\\1']; ?>";
		$this->T_R[] = "<?php foreach ((array)\$this->value['\\2'] as \$K => \$V) { ?>";
		$this->T_R[] = "<?php } ?>";
		$this->T_R[] = "<?php echo \$\\1; ?>";
		$this->T_R[] = "<?php if (\\1) { ?>";
		$this->T_R[] = "<?php }else if (\\2) { ?>";
		$this->T_R[] = "<?php } else { ?>";
		$this->T_R[] = "";
	}

	public function compile(){
		$this->c_var2();
		$this->c_staticFile();

		file_put_contents($this->comfile, $this->content);
	}

	public function c_var2(){
		$this->content = preg_replace($this->T_P, $this->T_R, $this->content);
	}

	public function c_staticFile(){
		$this->content = preg_replace('#\{\!(.* ?)\!\}#', '<script src=\\1'.'?t='.time().'></script>', $this->content);
	}

	public function __set($name, $value){
		$this->name = $value;
	}

	public function __get($name){
		return $this->$name;
	}
}

 ?>