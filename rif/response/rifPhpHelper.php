<?
class rifPhpHelper{

	private $hooks;
	private $path;	
	private $lng;
	private $vars = array();
	private $globalVars = array();
	private $event;
	private $rifStyles = array();
	private $rifScripts = array();

	public function __construct($path, rifInstance $instance, rifCore $rifCore, rifEvent $event, $scripts){
		$this->event = $event;
		$this->path = $path;
		$this->hooks = $rifCore->getHooks();
		$this->vars = $instance->instance['vars'];
		$this->rifScripts = $scripts;
		$this->lng = $rifCore->getLng();
		$this->vars['path'] = str_replace(PATH,$rifCore->getConfig()->getUrl(), $path);
		$this->vars['mainPath'] = $rifCore->getConfig()->getUrl();
		$this->hooks->add_filter('templateVars', array($this, "addTemplateVars"));
	}

	public function addTemplateVars($templateVars) {
		$extra = $this->vars;
		$templateVars = array_merge($templateVars, $extra);
		return $templateVars;
	}
	
	public function getHeader($template){
		if(file_exists($this->path."header.php")){
			include($this->path."header.php");
		}
	}
	public function getFooter($template){
		if(file_exists($this->path."footer.php")){
			include($this->path."footer.php");
		}
	}

	public function addStyle($style){
		$this->rifStyles[] = $style;
	}

	public function addScript($script){
		$this->rifScripts[] = $script;
	}

	public function renderStyles(){
		$this->event->run("rif.styles",$this->rifStyles);
	}

	public function renderScripts(){
		$this->event->run("rif.scripts",$this->rifScripts);
	}

	public function getVar($var){
		$vars = $this->getVars();
		if(isset($vars[$var])){
			return $vars[$var];
		}else{
			return "undefined";
		}
	}
	public function getVars($vars = array()){
		if($this->hooks->has_filter('templateVars')) {
			$vars = $this->hooks->apply_filters('templateVars', $vars);
		}
		return $vars;
	}
	public function getElement($template, $element){
		if(file_exists($this->path."element/".$element.".php")){
			include($this->path."element/".$element.".php");
		}
	}
}
?>