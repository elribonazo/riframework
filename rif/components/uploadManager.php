<?php
class uploadManager{

	private $uploadPath;
	private $uploadedFile;
	private $name;
	private $tmp_name;
	private $type;
	private $error;
	private $size;

	public function __construct(){
		if(!file_exists(PATH."/app/uploads")){
			mkdir(PATH."/app/uploads");
		}
		$this->_setUploadPath(PATH."/app/uploads");
	}

	public function newUpload($args){
		$this->reset();
		if(!isset($args['file']) || !isset($args['supportedTypes'])){
			rifException::uploadManagerException(array(
				'message'=> $this->config->getLng()->__('Invalid argument File & supportedTypes.')
			));
		}
		$supportedTypes = $args['supportedTypes'];
		$file = $args['file'];
		if(!isset($file['name']) || !isset($file['tmp_name']) || !isset($file['type']) || !isset($file['error']) || !isset($file['size'])){
			rifException::uploadManagerException(array(
				'message'=> $this->config->getLng()->__('Invalid $_FILE passed to uploadManager constructor.')
			));
		}
		$this->validate($supportedTypes,$file);
		$this->_setName($file['name']);
		$this->_setTmpName($file['tmp_name']);
		$this->_setType($file['type']);
		$this->_setError($file['error']);
		$this->_setSize($file['size']);
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$filename = md5($this->genUUID().$file['name']).".".$ext;
		try{
			move_uploaded_file($file['tmp_name'],$this->getUploadPath()."/".$filename);
			$this->_setUploadedFile($this->getUploadPath()."/".$filename);
		}catch(Exception $e){
			rifException::uploadManagerException(array(
				'message'=> $this->config->getLng()->__('Could not move the file to the directory __dir__.',array("dir"=>$this->getUploadPath()."/".$filename))
			));
		}
	}

	public function uploadPath($path){
		$this->_setUploadPath($path);
	}

	private function validate($supportedTypes,$file){
		$maxFileSize = $this->config->getUploadManagerFileSize();
		$supportedTypes = $this->config->getUploadManagerSupportedTypes();
		if($maxFileSize!==null){
			if($file['size'] > $maxFileSize){
				rifException::uploadManagerException(array(
					'message'=> $this->config->getLng()->__("The file exceeds the limit of __max__Kb.",array("max"=>$maxFileSize))
				));
			}
		}
		if($supportedTypes!==null){
			$supportedTypes = explode(",",$supportedTypes);
			if(count($supportedTypes)<=0){
				rifException::uploadManagerException(array(
					'message'=> $this->config->getLng()->__("Incorrect uploadManagerSupportedTypes parameter set in framework.ini.")
				));
			}
			if(!in_array($file['type'],$supportedTypes)){
				rifException::uploadManagerException(array(
					'message'=> $this->config->getLng()->__("Invalid file format.")
				));
			}
		}
		if(!file_exists($file['tmp_name'])){
			rifException::uploadManagerException(array(
				'message'=> $this->config->getLng()->__("The temporary file __file__ does not exist.",array("file"=>$file['tmp_name']))
			));
		}
		if($file['error'] !== 0){
			rifException::uploadManagerException(array(
				'message'=> $this->config->getLng()->__("An error ocurred while trying to upload a file")
			));
		}
	}

	private function reset(){
		$this->_setName(null);
		$this->_setTmpName(null);
		$this->_setType(null);
		$this->_setError(null);
		$this->_setSize(null);
	}


    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    private function _setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of tmp_name.
     *
     * @return mixed
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

    /**
     * Sets the value of tmp_name.
     *
     * @param mixed $tmp_name the tmp name
     *
     * @return self
     */
    private function _setTmpName($tmp_name)
    {
        $this->tmp_name = $tmp_name;

        return $this;
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
     * Gets the value of error.
     *
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
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
     * Gets the value of size.
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Sets the value of size.
     *
     * @param mixed $size the size
     *
     * @return self
     */
    private function _setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Gets the value of uploadPath.
     *
     * @return mixed
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    /**
     * Sets the value of uploadPath.
     *
     * @param mixed $uploadPath the upload path
     *
     * @return self
     */
    private function _setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;

        return $this;
    }

    /**
     * Gets the value of uploadedFile.
     *
     * @return mixed
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Sets the value of uploadedFile.
     *
     * @param mixed $uploadedFile the uploaded file
     *
     * @return self
     */
    private function _setUploadedFile($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }

    public function genUUID() {
		return sprintf ( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand ( 0, 0xffff ),
				mt_rand ( 0, 0xffff ),
				mt_rand ( 0, 0xffff ),
				mt_rand ( 0, 0x0fff ) | 0x4000,
				mt_rand ( 0, 0x3fff ) | 0x8000,
				mt_rand ( 0, 0xffff ),
				mt_rand ( 0, 0xffff ),
				mt_rand ( 0, 0xffff )
		);
	}
}