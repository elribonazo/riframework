<?
class rifRouting{

	private $route;
	private $error = false;
	private $error_msg;

	public function __construct(rifLng $lng, rifRequest $request, $routes){
		if(!is_array($routes)){
			rifException::configException(array(
				'message'=> $lng->__("Invalid routes format")
			));
		}
		$foundRoute = $this->findRoute($request, $routes);
		$this->_setRoute($this->validateRoute($foundRoute,$request,$lng));
	}

	/**
	 * [validateRoute description]
	 * @param  route
	 */
	public function validateRoute($route, rifRequest $request,rifLng $lng){
		if($route===false){
			$this->_setError(true);
			$this->_setErrorMsg($lng->__("__url__ - 404 Page not found", array("url"=>$request->getUrl())));
			return false;
		}
		if(class_exists($route['controller'])){
			if(!method_exists($route['controller'],$route['action'])){
				$this->_setError(true);
				$this->_setErrorMsg($lng->__("The method __action__ does not exist in controller __controller__",array("action"=>$route['action'],"controller" => $route['controller'])));
				return false;
			}
		}else{
			$this->_setError(true);
			$this->_setErrorMsg($lng->__("The controller __controller__ does not exist.", array("controller" => $route['controller'])));
			return false;
		}
		return $route;
	}
	
	/**
	 * [findRoute description]
	 * @return [type]
	 */
	public function findRoute(rifRequest $request, $routes){
		$validRoutes = array();
		$validRoutesWithVars = array();
		$requestExtension = pathinfo($request->getUrl());
		foreach($routes as $route){
			$controller = $route->getController()."Controller";
			$routeExtension = pathinfo($route->getUrl());
			$validExtension = false;

			if(isset($routeExtension['extension']) && isset($requestExtension['extension'])){
				if($routeExtension['extension'] === $requestExtension['extension']){
					$validExtension = true;
				}
			}else if(!isset($routeExtension['extension']) && !isset($requestExtension['extension'])){
				$validExtension = true;
			}
			if($validExtension === false) {
				continue;
			}
			if($route->getUrl() === $request->getUrl() && $route->getMethod() === $request->getMethod()){
				return array(
					"controller" => $controller, 
					"action" => $route->getAction(), 
					"vars" => $request->getVars(), 
					"response" => $route->getResponse()
				);
			}else{
				$routeUrl = preg_replace("/\.([a-zA-Z]+)/","", $route->getUrl());
				$requestUrl = preg_replace("/\.([a-zA-Z]+)/","", $request->getUrl());
				$explodeRequestUrl = explode("/", $requestUrl);
				$explodeRouteUrl = explode("/", $routeUrl);
				$explodeRequestUrl = array_values(array_filter($explodeRequestUrl));
				$explodeRouteUrl = array_values(array_filter($explodeRouteUrl));
				$routeScore = 0;
				$vars = array();
				$routeHasVars = false;
				if(count($explodeRequestUrl) !== count($explodeRouteUrl)){
					continue;
				}
				for($i = 0; $i < count($explodeRequestUrl); $i++){
					if($explodeRequestUrl[$i] === $explodeRouteUrl[$i]){
						//A static match found
						$routeScore++;
					}else if(preg_match("/(\[([a-zA-Z0-9_-]+)\,([a-zA-Z0-9]+)\]+)/",$explodeRouteUrl[$i], $matches)){
						if(isset($matches[2]) && isset($matches[3])){
							if($matches[3] === "string" && is_string($explodeRequestUrl[$i])){
								$routeScore++;
								$vars[$matches[2]] = $explodeRequestUrl[$i];
								$routeHasVars = true;
							}else if($matches[3] === "integer" && is_numeric($explodeRequestUrl[$i])){
								$routeScore++;
								$vars[$matches[2]] = $explodeRequestUrl[$i];
								$routeHasVars = true;
							}
						}
					}	
				}
				if(count($explodeRequestUrl) === $routeScore && $route->getMethod() === $request->getMethod()){
					$validRoutesWithVars[] = array(
						"controller" => $controller, 
						"action" => $route->getAction(), 
						"vars" => array_merge($vars, $request->getVars()), 
						"response" => $route->getResponse()
					);			
				}	
			}
		}
		if(count($validRoutesWithVars)>0){
			return $validRoutesWithVars[0];
		}
		return false;
	}	

    /**
     * Gets the value of route.
     *
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Sets the value of route.
     *
     * @param mixed $route the route
     *
     * @return self
     */
    private function _setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Sets the value of error.
     *
     * @param mixed $error the error
     *
     * @return self
     */
    private function _setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Gets the value of error_msg.
     *
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->error_msg;
    }

    /**
     * Sets the value of error_msg.
     *
     * @param mixed $error_msg the error msg
     *
     * @return self
     */
    private function _setErrorMsg($error_msg)
    {
        $this->error_msg = $error_msg;

        return $this;
    }

    /**
     * Gets the value of error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
}
?>