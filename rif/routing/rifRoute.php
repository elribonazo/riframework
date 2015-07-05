<?
class rifRoute{
	
	private $method;
	private $url;
	private $action;
	private $response;
	private $controller;

	public function __construct($controller, $route){
		$this->_setMethod($route['method']);
		$this->_setUrl($route['url']);
		$this->_setAction($route['action']);
		$this->_setResponse($route['response']);
		$this->_setController($controller);
	}	

	public function validate(){
		$required = array("method","url","action","response");
		foreach($required as $field){
			if(!isset($this->$field) || empty($this->$field)){
				return $field;
			}
		}
		return true;
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

    /**
     * Gets the value of action.
     *
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the value of action.
     *
     * @param mixed $action the action
     *
     * @return self
     */
    private function _setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Gets the value of response.
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the value of response.
     *
     * @param mixed $response the response
     *
     * @return self
     */
    private function _setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Gets the value of controller.
     *
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Sets the value of controller.
     *
     * @param mixed $controller the controller
     *
     * @return self
     */
    private function _setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

}
?>