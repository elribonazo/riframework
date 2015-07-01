<?php 
class rifController{

	private $adminNavigation = array();
	private $vars = array();
	private $hooks;
	private $route;
	private $config;
	private $globalVars = array();

	public function __construct(rifCore $rifCore){
		$this->config = $rifCore->core['config'];
		$this->route = $rifCore->core['routing']->route;
		$this->hooks = $rifCore->core['hooks'];
	}
	
	public function setVar($var, $val){
		if($this->hooks->has_filter($this->route['action']."_var_".$var)) {
			$this->vars[$var] = $this->hooks->apply_filters($this->route['action']."_var_".$var,$val);
		}else{
			$this->vars[$var] = $val;
		}
	}

	public function getVars(){
		return array("vars" =>$this->vars, "globalVars" => $this->globalVars);
	}
}
?>