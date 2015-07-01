<?
class rifRoute{
	
	public $method;
	public $url;
	public $action;
	public $response;
	public $controller;

	public function __construct($controller, $route){

		$this->method = $route['method'];
		$this->url = $route['url'];
		$this->action = $route['action'];
		$this->response = $route['response'];
		$this->controller = $controller;
	
	}


}
?>