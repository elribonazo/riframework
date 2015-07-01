<?php
class rifShell{

	public $rifCore;
	public $shell;
	public $controller;
	public $model;
	public $component;
	public $lng;

	public function __construct(rifCore $rifCore){
		$this->rifCore = $rifCore;
		$this->lng = $rifCore->core['lng'];
	}

	public function Controller($controller){
		$this->controller->$controller = new $controller();
	}

	public function Component($component){
		$this->component->$component = new $component();
	}

	public function execute($argv){
		if(php_sapi_name() == 'cli'){
			$shellCommand = $argv[1];
			$reflectionShell = new ReflectionClass($shellCommand);
			$shellAnotations = rifAnotations::getAnnotations($reflectionShell->getDocComment());
			$shell = new $shellCommand();
			$shell->lng = $this->lng;
			unset($argv[0]);
			unset($argv[1]);
			$argv = array_values($argv);
			if(is_array($shellAnotations) && count($shellAnotations)){
				if(!is_array($shellAnotations['param'])){
					$shellParam = explode(" ", $shellAnotations["param"]);
					if(isset($argv[$shellParam[0]])){
						$shell->$shellParam[1] = $argv[$shellParam[0]];
					}
				}else{
					foreach($shellAnotations['param'] as $shellParam){
						$shellParam = explode(" ", $shellParam);
						if(isset($argv[$shellParam[0]])){
							$shell->$shellParam[1] = $argv[$shellParam[0]];
						}
					}
				}
			}
			$rifModel = "rifModel";
			if(isset($shell->models) && is_array($shell->models)){
				foreach($shell->models as $model){
					$shell->$model = new $rifModel($this->rifCore->core['config'], new $model);
				}
			}
			if(isset($shell->components) && is_array($shell->components)){
				foreach($shell->components as $component){
					$shell->$component = new $component;
				}
			}
			$shell->run($this->rifCore);
		}else{
			rifException::callableException(array(
				'message' => $this->lng->__("Incorrect command execution")
			));
		}	
	}
}
?>