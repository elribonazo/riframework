<?
class rifRequest{

	public $vars = array();
	public $method;
	public $url;

	public function __construct(rifLng $lang){
		$this->setRequestVars();
	}
	
	/**
	 * [getRequestVars description]
	 */
	public function setRequestVars(){

		$this->method = $_SERVER['REQUEST_METHOD'];
		if(!isset($_GET['_route'])){
			$this->url = "/";
		}else{
			$this->url = $_GET['_route'];
			unset($_GET['_route']);
		}
		foreach($_FILES as $var => $val){
			$this->vars[$var] = $val;
		}
		foreach($_POST as $var => $val){
			$this->vars[$var] = $val;
		}
		foreach($_GET as $var => $val){
			$this->vars[$var] = $val;
		}
		if($this->method === "PUT" || $this->method === "DELETE"){
			if($this->method === "PUT"){
				$_PUT = array();
				parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);
				foreach($_PUT as $var => $val){
					$this->vars[$var] = $val;
				}
			}
			if($this->method === "DELETE"){
				$_DELETE = array();
				parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE);
				foreach($_DELETE as $var => $val){
					$this->vars[$var] = $val;
				}
			}
		}
	}
}
?>