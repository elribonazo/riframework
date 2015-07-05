<?
class rifConfig{

	private $url;
	private $templating;
	private $theme;
	private $publicKey;
	private $privateKey;
	private $user;
	private $database;
	private $host;
	private $password;
	private $routes;
	private $lng;

	public function __construct($configArray,rifLng $lng){
		$this->_setLng($lng);
		$this->validateRequiredConfigParameters($configArray);
	}

	private function validateRequiredConfigParameters($configArray){
		$required = array(
			'database'=>array(
				'user',
				'password',
				'database',
				'host'
			),
			'main'=>array(
				"url",
				"templating",
				"theme",
				"publicKey",
				"privateKey"
			)
		);
		foreach($required as $field => $childs){
			if(!array_key_exists($field,$configArray)){
				rifException::rifConfigException(array(
					'message' => $this->getLng()->__("Invalid framework configuration. The parameter __param__ is not correctly set in the config File",array("param"=>$field))
				));
			}else{
				foreach($childs as $child){
					if(!array_key_exists($child,$configArray[$field])){
						rifException::rifConfigException(array(
						'message' => $this->getLng()->__("Invalid framework configuration. The parameter __param__ is not correctly set in the config File",array("param"=>$child))
						));
					}
				}
			}
		}
		$this->_setUrl($configArray['main']['url']);
		$this->_setTemplating($configArray['main']['templating']);
		$this->_setTheme($configArray['main']['theme']);
		$this->_setPublicKey($configArray['main']['publicKey']);
		$this->_setPrivateKey($configArray['main']['privateKey']);
		$this->_setUser($configArray['database']['user']);
		$this->_setPassword($configArray['database']['password']);
		$this->_setHost($configArray['database']['host']);
		$this->_setDatabase($configArray['database']['database']);
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
     * Gets the value of templating.
     *
     * @return mixed
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * Sets the value of templating.
     *
     * @param mixed $templating the templating
     *
     * @return self
     */
    private function _setTemplating($templating)
    {
        $this->templating = $templating;

        return $this;
    }

    /**
     * Gets the value of theme.
     *
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Sets the value of theme.
     *
     * @param mixed $theme the theme
     *
     * @return self
     */
    private function _setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Gets the value of isInstalled.
     *
     * @return mixed
     */
    public function getIsInstalled()
    {
        return $this->isInstalled;
    }

    /**
     * Sets the value of isInstalled.
     *
     * @param mixed $isInstalled the is installed
     *
     * @return self
     */
    private function _setIsInstalled($isInstalled)
    {
        $this->isInstalled = $isInstalled;

        return $this;
    }

    /**
     * Gets the value of publicKey.
     *
     * @return mixed
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Sets the value of publicKey.
     *
     * @param mixed $publicKey the public key
     *
     * @return self
     */
    private function _setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    /**
     * Gets the value of privateKey.
     *
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Sets the value of privateKey.
     *
     * @param mixed $privateKey the private key
     *
     * @return self
     */
    private function _setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    /**
     * Gets the value of framework.
     *
     * @return mixed
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * Sets the value of framework.
     *
     * @param mixed $framework the framework
     *
     * @return self
     */
    private function _setFramework($framework)
    {
        $this->framework = $framework;

        return $this;
    }

    /**
     * Gets the value of routes.
     *
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Sets the value of routes.
     *
     * @param mixed $routes the routes
     *
     * @return self
     */
    private function _setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Gets the value of lng.
     *
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Sets the value of lng.
     *
     * @param mixed $lng the lng
     *
     * @return self
     */
    private function _setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Gets the value of user.
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the value of user.
     *
     * @param mixed $user the user
     *
     * @return self
     */
    private function _setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the value of database.
     *
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Sets the value of database.
     *
     * @param mixed $database the database
     *
     * @return self
     */
    private function _setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Gets the value of host.
     *
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the value of host.
     *
     * @param mixed $host the host
     *
     * @return self
     */
    private function _setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Gets the value of password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the value of password.
     *
     * @param mixed $password the password
     *
     * @return self
     */
    private function _setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
?>