<?
class rifconfig{
	public $framework;
	public $routes;
	public $lng;
	
	public function __construct(rifLng $lng){
		$this->lng = $lng;
		$mainPath = $mainPath = dirname(dirname(dirname(__FILE__)));
		$this->framework = $this->parseIni($mainPath."/rif/config/framework.ini");
		if(file_exists($mainPath."/app/routes/routes.php")){
			$routes = $this;
			require_once($mainPath."/app/routes/routes.php");
		}else{
			rifException::configException(array(
				'message' => $this->lng->__("Routes file does not exist.")
			));
		}
	}

	public function addRoute($controller, $method, $url, $action, $response){
		$this->routes[] = new rifRoute($controller, array(
			'method' => $method,
			'action' => $action,
			'url' => $url,
			'response' => $response
		));
	}

	public function parseIni($file){
		$ini = array();
		if(file_exists($file)){
			$ini = parse_ini_file($file, true);
		}else{
			rifException::configException(array(
				'message' => $this->lng->__("The .ini file (__file__) does not exist.",array("file"=>$file))
			));
		}
		return $ini;
	}
}
?>