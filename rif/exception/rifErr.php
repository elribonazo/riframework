<?php
class rifErr{

	public $message;
	public $code;
    public $error_type;

	public function __construct($e){
        $parsed = $this->parse($e->getMessage());
		$this->_setMessage($parsed->message);
		$this->_setCode($e->getCode());
        $this->_setErrorType($parsed->errorType);
	}

	private function parse($e){
		$e = json_decode($e);
		return $e;
	}

    /**
     * Gets the value of message.
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value of message.
     *
     * @param mixed $message the message
     *
     * @return self
     */
    private function _setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the value of code.
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets the value of code.
     *
     * @param mixed $code the code
     *
     * @return self
     */
    private function _setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets the value of error_type.
     *
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->error_type;
    }

    /**
     * Sets the value of error_type.
     *
     * @param mixed $error_type the error type
     *
     * @return self
     */
    public function _setErrorType($error_type)
    {
        $this->error_type = $error_type;

        return $this;
    }
}
?>