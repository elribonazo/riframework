<?
/**
 * Rif Instance
 *
 * Gets the main Controller, all his models, components and subcomponents
 * So that any Controller can call any model, utility or component
 * Any Controller and utility can use a model
 * Models can't use Controller or components
 * It also loads the rifCore instance to all the components that are instantiated
 */
class rifInstance{

	public $instance;
	private $lng;

	public function __construct(rifCore $rifCore){
		$rifRouting = $rifCore->core['routing'];
		$rifConfig = $rifCore->core['config'];
		$rifHooks = $rifCore->core['hooks'];
		$this->lng = $rifCore->core['lng'];
		$params = $this->getMethodParameters($rifRouting);
		$rifController = "rifController";
		$instance = new $rifRouting->route['controller']();
		$rifModel = "rifModel";
		$this->loadPlugins($rifCore);
		$rifController = new $rifController($rifCore);
		$instance->controller = $rifController;
		$instance = $this->loadRecursiveModules($instance, $rifModel, $rifCore);
		$instance->hooks =  $rifHooks;
		$instance->lng = $rifCore->core['lng'];
		$controllerResponse = call_user_func_array(array($instance, $rifRouting->route['action']), $params);
		$instance = $this->includeGlobalVars($controllerResponse, $rifRouting, $instance, $params);
		$this->instance = $instance;
	}

	private function includeGlobalVars($controllerResponse,rifRouting $rifRouting, $instance, $params){
		$controllerResponse['globalVars'] = $this->getMethodGlobalVariables($rifRouting, $instance, $params);
		$controllerResponse['vars'] = (isset($controllerResponse['vars'])) ?
			array_merge($controllerResponse['globalVars'], $controllerResponse['vars']):
			$controllerResponse['globalVars'];
		return $controllerResponse;
	}

	private function getMethodGlobalVariables(rifRouting $rifRouting, $instance, $inputVars){
		$controller = $rifRouting->route['controller'];
		$action = $rifRouting->route['action'];
		$class = new ReflectionClass($controller);
		$methodAnotations = rifAnotations::getAnnotations($class->getMethod($action)->getDocComment());
		$globals = array();
		if(isset($methodAnotations['global'])){
			if(is_array($methodAnotations['global'])){
				foreach($methodAnotations['global'] as $param){
					$anotationParams = explode(" ", $param);
					if(count($anotationParams) >= 4 && $anotationParams[1] === "call"){
						$callParams = array();
						$globals = $this->getMethodVar($anotationParams, $globals, $instance, $callParams);
					}else if(count($anotationParams) === 3 && $anotationParams[1] === "input"){
						$globals = $this->getMethodInputVar($anotationParams, $globals, $class, $inputVars, $action);
					}else if(count($anotationParams) === 2){
						$globals = $this->getStaticGlobalVar($anotationParams, $globals);
					}
				}
			}else{
				$anotationParams = explode(" ", $methodAnotations['global']);
				if(count($anotationParams) >= 4 && $anotationParams[1] === "call"){
					$callParams = array();
					$globals = $this->getMethodVar($anotationParams, $globals, $instance, $callParams);
				}else if(count($anotationParams) === 3 && $anotationParams[1] === "input"){
					$globals = $this->getMethodInputVar($anotationParams, $globals, $class, $inputVars, $action);
				}else if(count($anotationParams) === 2){
					$globals = $this->getStaticGlobalVar($anotationParams, $globals);
				}
			}
		}
		return $globals;
	}

	private function getMethodInputVar($anotationParams, $globals, $class, $inputVars, $action){
		foreach($class->getMethods() as $method){
			if($method->getName() === $action){
				$methodInputParameters = $method->getParameters();
				for($i=0;$i<count($methodInputParameters);$i++){
					if($anotationParams[2] === $methodInputParameters[$i]->name){
						$globals[$anotationParams[0]] = $inputVars[$i];
					}
				}
			}
		}
		return $globals;
	}

	private function getMethodVar($anotationParams, $globals, $instance, $callParams){
		for($i = 4; $i < count($anotationParams); $i++){
			$callParams[] = $anotationParams[$i];
		}
		if(isset($instance->$anotationParams[2]) && method_exists($instance->$anotationParams[2], $anotationParams[3])){
			$globals[$anotationParams[0]] = call_user_func_array(array($instance->$anotationParams[2],$anotationParams[3]), $callParams);
		}
		return $globals;
	}

	private function getStaticGlobalVar($anotationParams, $globals){
		$globals[$anotationParams[0]] = $anotationParams[1];
		return $globals;
	}

	/**
	 * [loadRecursiveModules Loads all the Controller, component or models recursively to the 1st instance class]
	 * @param  controller or utils $instance
	 * @param  string $rifModel
	 * @param  rifCore $rifCore
	 * @return instance
	 */
	private function loadRecursiveModules($instance, $rifModel, $rifCore){
		if(isset($instance->models)){
			foreach($instance->models as $model){
				$instance->$model = new $rifModel($rifCore, new $model);
			}
		}
		if(isset($instance->components)){
			foreach($instance->components as $component){
				$instanceComponent = $this->loadRecursiveModules(new $component(), $rifModel, $rifCore);
				$instanceComponent->config = $rifCore->core['config'];
				if(isset($instanceController->models)){
					foreach($instanceComponent->models as $extraModel){
						$instanceComponent->$extraModel = new $rifModel($rifCore, new $extraModel);
					}
				}
				$instance->$component = $instanceComponent;
			}
		}
		return $instance;
	}

	/**
	 * [getMethodParameters Gets the method parameters using the ReflectionClass]
	 * @return array
	 */
	private function getMethodParameters(rifRouting $rifRouting){
		$controller = $rifRouting->route['controller'];
		$action = $rifRouting->route['action'];
		$class = new ReflectionClass($controller);
		$method = $class->getMethod($action);
		$required_params = array();
		$methodAnotations = rifAnotations::getAnnotations($class->getMethod($action)->getDocComment());
		if(isset($methodAnotations['required'])){
			if(is_array($methodAnotations['required'])){
				foreach($methodAnotations['required'] as $var){
					$required_params[] = $var;
				}
			}else{
				$required_params[] = $methodAnotations['required'];
			}
		}
		$parameters = $method->getParameters();
		$params_new = array();
		$params_old = $rifRouting->route['vars'];
		for($i = 0; $i<count($parameters);$i++){
			$key = $parameters[$i]->getName();
			$headerKey = "HTTP_".strtoupper($key);
			if(array_key_exists($key,$params_old)){
				$params_new[$i] = $params_old[$key];
			}else if(array_key_exists($headerKey, $_SERVER)){
				$params_new[$i] = $_SERVER["HTTP_".strtoupper($key)];
			}else{
				if(in_array($key, $required_params)){
					rifException::instanceException(array(
						'message' => $this->lng->__("The method __action__ (__controller__) has  a required parameter __key__ that is not defined",array(
							'controller' => $controller,
							'action' => $action,
							'key' => $key
						))
					));
				}
			}
		}
		return $params_new;
	}

	private function loadPlugins(rifCore $rifCore){
		$config = $rifCore->core['config'];
		$hooks = $rifCore->core['hooks'];
		$plugins = $this->loadPluginDirectories($config);
		foreach($plugins as $plugin){
			if($plugin !== "." && $plugin !== ".."){
				$matches = array();
				if(preg_match_all("/^(.*)\.(php)$/i", $plugin, $matches)){
					if(isset($matches[1][0]) && isset($matches[1][0])) {
						$this->loadPlugin($matches[1][0], $hooks);
					}
				}
			}	
		}
	}

	private function loadPlugin($plugin, hooks $hooks){
		$plugin = basename($plugin);
		$pluginObj = null;
		$rifPlugin = "rifPlugin";
		try{
			$pluginReflection = new ReflectionClass($plugin);
			$pluginObj = new $plugin();
			$pluginObj->plugin = new $rifPlugin();
			$pluginObj->plugin->hooks = $hooks;
			$methods = $pluginReflection->getMethods();
			foreach($methods as $method){
				$methodName = $this->getMethodName($method);
				if($methodName === "__hookVar"){
					$pluginObj->$methodName();
				}
			}
		}catch(ReflectionException $e){
			if($e->getCode() === -1){
				rifException::instanceException(array(
					'message' => $this->lng->__("Invalid Plugin __plugin__",array(
						"plugin" => $plugin
					))
				));
			}
		}
	}

	private function getMethodName(ReflectionMethod $method){
		return $method->name;
	}

	private function loadPluginDirectories(rifConfig $config){
		if(file_exists(PATH."/app/plugins")){
			return glob(PATH."/app/plugins/" . '*.{php}',GLOB_BRACE);
		}
	}
}
?>