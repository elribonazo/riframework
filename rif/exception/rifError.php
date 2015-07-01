<?php
class rifError{
    public static function  __callStatic($errorType, $attrs = array()){
    	$attrs[0]->errorType = $errorType;
        throw new rifExceptionCallable($attrs[0]);
    }
}
?>