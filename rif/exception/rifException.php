<?php
class rifException{

	public $error;

	public static function __callStatic($method, $attrs){
		$error = new rifErrObj();
		if(isset($attrs[0])){
			foreach($attrs[0] as $attr => $value) {
				$error->$attr = $value;
			}
		}
		$error->errorType = $method;
		rifError::$method($error);
	}
	
}
?>