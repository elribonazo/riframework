<?
class rifRouting{

	public $route;
	public $routes;
	private $error = false;
	private $error_msg;
	public $lng;

	public function __construct(rifLng $lng, rifRequest $request, $routes){
		$this->lng = $lng;
		if(!is_array($routes)){
			rifException::configException(array(
				'message'=> $this->lng->__("Invalid routes format")
			));
		}
		$this->validateRoute($this->findRoute($request, $routes), $request);
	}

	public function hasError(){
		return $this->error;
	}

	public function getError(){
		return $this->error_msg;
	}

	/**
	 * [validateRoute description]
	 * @param  route
	 */
	public function validateRoute($route, rifRequest $request){
		if($route===false){
			$this->error = true;
			$this->error_msg = $this->lng->__("__url__ - 404 Page not found", array("url"=>$request->url));
			return false;
		}
		if(class_exists($route['controller'])){
			if(!method_exists($route['controller'],$route['action'])){
				$this->error = true;
				$this->error_msg = $this->lng->__("The method __action__ does not exist in controller __controller__",array("action"=>$route['action'],"controller" => $route['controller']));
				return false;
			}
		}else{
			$this->error = true;
			$this->error_msg = $this->lng->__("The controller __controller__ does not exist.", array("controller" => $route['controller']));
			return false;
		}
		$this->route = $route;
	}
	
	/**
	 * [findRoute description]
	 * @return [type]
	 */
	public function findRoute($request, $routes){
		$validRoutes = array();
		$validRoutesWithVars = array();
		$requestExtension = pathinfo($request->url);
		foreach($routes as $route){	
			if(!isset($route->url)) continue;
			$controller = $route->controller."Controller";
			$routeExtension = pathinfo($route->url);
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
			if($route->url === $request->url && $route->method === $request->method){
				return array(
					"controller" => $controller, 
					"action" => $route->action, 
					"vars" => $request->vars, 
					"response" => $route->response
				);
			}else{
				$routeUrl = preg_replace("/\.([a-zA-Z]+)/","", $route->url);
				$requestUrl = preg_replace("/\.([a-zA-Z]+)/","", $request->url);
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
				if(count($explodeRequestUrl) === $routeScore && $route->method === $request->method){
					$validRoutesWithVars[] = array(
						"controller" => $controller, 
						"action" => $route->action, 
						"vars" => array_merge($vars, $request->vars), 
						"response" => $route->response
					);			
				}	
			}
		}
		if(count($validRoutesWithVars)>0){
			return $validRoutesWithVars[0];
		}
		return false;
	}	
}
?>