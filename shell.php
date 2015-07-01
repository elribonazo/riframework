<?php
try{
	define("DEBUG",true);
	require_once(dirname(__FILE__) . "/rif/loader/autoloader.php");
	autoloader::autoload();
	$riframework = new riframework();
	$riframework->shell(isset($argv)?$argv:array());
}catch(Exception $e){
	if(DEBUG){
		$error = new rifErr($e);
		echo json_encode($error);
	}
}
?>