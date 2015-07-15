<?php
class generateGlobalMethods{
	
	private $reflection;
	private $content = "";

	public function run(rifCore $rifCore){
		$this->getContent($rifCore);

	}

	private function writeResult($content,$path){
		if(!file_exists($path)) mkdir($path);
		if(!file_exists($path."/assets")) mkdir($path."/assets");
		if(!file_exists($path."/assets/js")) mkdir($path."/assets/js");
		$fp=fopen($path."/assets/js/riframework.js","w");

		$document = "(function(rifXhr){"."\r\n";
		$document .= "\"use strict\";"."\r\n";
		$document .= $content."\r\n";
		$document .= "}(this));";
		
		fwrite($fp,$document);
		fclose($fp);
	}

	private function getContent(rifCore $rifCore){
		$routes = $rifCore->getConfig()->getRoutes();
		$template = $this->loadMethodTemplate("request.js");
		foreach($routes as $route){
			if($route->getResponse() !== "json") continue;
			$this->newMethod($route,$template,$rifCore);
		}
		$this->writeResult($this->content,PATH."/app/template/".$rifCore->getConfig()->getTheme());
	}

	private function setReflectionClass($controller){
		try{
			$this->reflection = new ReflectionClass($controller);
		}catch(Exception $e){
			rifException::generateGlobalMethodsException(array(
				'message'=> $this->lng->__("The route to the controller, or the method could not be loaded.")
			));
		}
	}

	private function newMethod(rifRoute $call,$template,rifCore $rifCore){
		$controller = $call->getController()."Controller";
		$action = $call->getAction();
		$method = $call->getMethod();
		$this->setReflectionClass($controller);
		$methodParams = $this->getMethodParams($action);
		$vars = array();
		$vars['requestUrl'] = $rifCore->getConfig()->getUrl() . $call->getUrl();
		$vars['requestType'] = $call->getMethod();
		$vars['required'] = json_encode($methodParams);
		$vars['functionName'] = "xhr".ucfirst($action);
		$matches = array();
		if(preg_match_all("/(\[([a-zA-Z0-9_-]+)\,([a-zA-Z0-9]+)\]+)/",$vars['requestUrl'],$matches)){
			$placeholders = array();
			$replacements = array();
			if(count($matches) === 4){
				$placeholders[] = "/\[".$matches[2][0]."\,".$matches[3][0]."\]/";
				$replacements[] = "' + parameters.".$matches[2][0]." + '";
			}
			$vars['requestUrl'] = preg_replace($placeholders,$replacements,$vars['requestUrl']);
		}
		$this->content .= "\r\n" . $this->getMethodContent($template,$vars);
	}

	private function loadMethodTemplate($file){
		if(file_exists(PATH."/rif/methodTemplates/".$file)){
			return file_get_contents(PATH."/rif/methodTemplates/".$file);
		}else{
			rifException::generateGlobalMethodsException(array(
				'message'=> $this->lng->__("The requested methodTemplate does not exist.")
			));
		}
	}

	private function getMethodContent($template,$vars){
		return $this->replacePlaceholders($template,$vars);
	}

	private function replacePlaceholders($template,$vars){
		$matches = array();
		$placeholders = array();
		$replacements = array();
		if(preg_match_all("/\{\{([A-Za-z0-9]+)\}\}/xi",$template,$matches)){
			if(isset($matches[1])){
				foreach($matches[1] as $placeholder){
					if(isset($vars[$placeholder])){
						$placeholders[] = "/\{\{".$placeholder."\}\}/";
						$replacements[] = $vars[$placeholder];
					}
				}
			}
			$template = preg_replace($placeholders,$replacements,$template);
		}
		return $template;
	}

	private function getMethodParams($method){
		$fields = array();
		$reflectionMethods = $this->reflection->getMethods();
		foreach($reflectionMethods as $reflectionMethod){
			if($reflectionMethod->name !== $method) continue;
			$parameters = $reflectionMethod->getParameters();
			foreach($parameters as $parameter){
				if(!$parameter->isOptional()){
					$fields[] = $parameter->name;
				}
			}
		}
		return $fields;
	}
}