<?
class rifCallable{

	public function __construct(rifCore $rifCore){
		$this->config = $rifCore->core['config'];
	}
  
	public function __call($name, $args) {
    if (is_callable($this->$name)) {
      array_unshift($args, $this);
      return call_user_func_array($this->$name, $args);
    }else{
      rifException::callableException(array(
        'code'=>10,
        'message' => "Undefined method " . $name
      ));
    }
  }
}
?>