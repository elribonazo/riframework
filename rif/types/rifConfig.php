<?
class rifConfig{

	public function __construct(rifLng $lng){
        $this->lng = $lng;
        $configFile = new rifFile($lng,PATH."/rif/config/framework.ini",true);
        $routesFile = new rifFile($lng,PATH."/app/routes/routes.php",true);
        if($configFile->getExists()){
            $configurationParameters = parse_ini_file($configFile->getPath(),true);
            foreach($configurationParameters as $group => $params){
                foreach($params as $param => $value){
                    $function = "set".$this->camelCase(ucfirst($param));
                    $this->$function($value);
                }
            }
            if($routesFile->getExists()){
                $routes = $this;
                require_once($routesFile->getPath());
            }
            
        } 
	}

    public function camelCase($str, array $noStrip = array()){
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);
        return $str;
    }

    public function __call($method, $args = array()){
        $matches = array();
        if(preg_match_all("/get([A-Za-z0-9]+)/",$method,$matches)){
            if(isset($matches[1])){
                $methodName = lcfirst($matches[1][0]);
                if(isset($this->$methodName)){
                    return $this->$methodName;
                }else{
                    return null;
                }
            }
        }else if(preg_match_all("/set([A-Za-z0-9]+)/",$method,$matches)){
            if(isset($matches[1])){
                $methodName = lcfirst($matches[1][0]);
                $this->$methodName = (isset($args[0])) ? $args[0] : null;
            }
        }else{
            rifException::rifRouteException(array(
                'message' => $this->getLng()->__("Invalid method name rifConfig (__method__).",array("method"=>$method))
            ));
        }
    }

	public function addRoute($controller, $method, $url, $action, $response){
		$route = new rifRoute($controller,array(
			'method' => $method,
			'url' => $url,
			'action' => $action,
			'response' => $response
		));
		$valid = $route->validate();
		if($valid === true){
			$this->routes[] = $route;
		}else{
			rifException::rifRouteException(array(
				'message' => $this->getLng()->__("Invalid route. The parameter __param__ is not correctly set in the route.",array("param"=>$valid))
			));
		}
	}

}
?>