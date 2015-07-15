<?
class rifFile{

	private $type;
	private $path;
	private $exists;
	private $checkPath;
	private $lng;

	public function __construct($lng,$path,$checkPath = true){
		$this->_setType("read");
		$this->_setPath($path);
		$this->_setCheckPath($checkPath);
		$this->_setLng($lng);
		$this->_setPath($path);
		$this->validate();
	}

	private function validate(){
		if(!file_exists($this->getPath())){
			if($this->getCheckPath()){
				rifException::fileException(array(
					'message' => $this->getLng()->__("The file (__file__) does not exist.",array("file"=>$this->getPath()))
				));
			}
			$this->_setExists(false);
		}else{
			$this->_setExists(true);
		}
	}

    /**
     * Gets the value of type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of type.
     *
     * @param mixed $type the type
     *
     * @return self
     */
    private function _setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Gets the value of path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the value of path.
     *
     * @param mixed $path the path
     *
     * @return self
     */
    private function _setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Gets the value of checkPath.
     *
     * @return mixed
     */
    public function getCheckPath()
    {
        return $this->checkPath;
    }

    /**
     * Sets the value of checkPath.
     *
     * @param mixed $checkPath the check path
     *
     * @return self
     */
    private function _setCheckPath($checkPath)
    {
        $this->checkPath = $checkPath;

        return $this;
    }

    /**
     * Gets the value of exists.
     *
     * @return mixed
     */
    public function getExists()
    {
        return $this->exists;
    }

    /**
     * Sets the value of exists.
     *
     * @param mixed $exists the exists
     *
     * @return self
     */
    private function _setExists($exists)
    {
        $this->exists = $exists;

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
}
?>