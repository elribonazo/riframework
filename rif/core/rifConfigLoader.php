<?
class rifConfigLoader{

	private $config;
	
	public function __construct(rifLng $lng){
		$configFile = new rifFile($lng,PATH."/rif/config/framework.ini",true);
		$routesFile = new rifFile($lng,PATH."/app/routes/routes.php",true);
		if($configFile->getExists()){
			$config = new rifConfig(parse_ini_file($configFile->getPath(),true),$lng);
			if($routesFile->getExists()){
				$routes = $config;
				require_once($routesFile->getPath());
			}
			$this->_setConfig($config);
		}	
	}

    /**
     * Gets the value of config.
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the value of config.
     *
     * @param mixed $config the config
     *
     * @return self
     */
    private function _setConfig($config)
    {
        $this->config = $config;
        return $this;
    }

}
?>