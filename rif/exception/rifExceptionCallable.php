<?php
class rifExceptionCallable extends Exception{
	public function __construct($args){
		$code = (isset($args->code)) ? $args->code : 0;
		logger::error($args);
		parent::__construct(json_encode($args), $code, null);
	}
}
?>