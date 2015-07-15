<?
class rifRequest{

	private $vars = array();
	private $method;
	private $url;

	public function __construct(){
		$this->setRequestVars();
	}
	
	/**
	 * [getRequestVars description]
	 */
	public function setRequestVars(){
        
		$this->_setMethod($_SERVER['REQUEST_METHOD']);
		if(!isset($_GET['_route'])){
			$this->_setUrl("/");
		}else{
			$this->_setUrl($_GET['_route']);
			unset($_GET['_route']);
		}
		foreach($_FILES as $var => $val){
			$this->_setVar($var,$val);
		}
		foreach($_POST as $var => $val){
			$this->_setVar($var,$val);
		}
		foreach($_GET as $var => $val){
			$this->_setVar($var,$val);
		}

		if($this->method === "PUT" || $this->method === "DELETE"){
			if($this->method === "PUT"){
				$_PUT = array();
				parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);
				foreach($_PUT as $var => $val){
					$this->_setVar($var,$val);
				}
			}
			if($this->method === "DELETE"){
				$_DELETE = array();
				parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_DELETE);
				foreach($_DELETE as $var => $val){
					$this->_setVar($var,$val);
				}
			}
		}
	}

	private function _setVar($var,$val){
		$this->vars[$var] = $val;
	}

    /**
     * Gets the value of vars.
     *
     * @return mixed
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Sets the value of vars.
     *
     * @param mixed $vars the vars
     *
     * @return self
     */
    private function _setVars($vars)
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * Gets the value of method.
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the value of method.
     *
     * @param mixed $method the method
     *
     * @return self
     */
    private function _setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param mixed $url the url
     *
     * @return self
     */
    private function _setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
?>