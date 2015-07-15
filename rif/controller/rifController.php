<?php 
class rifController{

	private $vars = array();
	private $hooks;
	private $route;
	private $config;
	private $globalVars = array();

	public function __construct(rifCore $rifCore){
		$this->config = $rifCore->getConfig()->getConfig();
		$this->route = $rifCore->getRouting()->getRoute();
		$this->hooks = $rifCore->getHooks();
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

	public function newComponent($component, $args){
		try{
			$reflectionClass = new ReflectionClass($component);
			$args['config'] = $this->config;
			$reflectionClass->newInstance($args);
    	}catch(Exception $e){
    		rifException::componentLoaderException(array(
				'message'=> $this->lng->__("The component __component__ could not be loaded",array("component"=>$component))
			));
    	}
	}
	
}
?>