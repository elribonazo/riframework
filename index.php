<?php

	error_reporting(E_ALL);
	require_once(dirname(__FILE__) . "/rif/loader/autoloader.php");
	autoloader::autoload();
	$riframework = new riframework();
	$riframework->framework();

?>