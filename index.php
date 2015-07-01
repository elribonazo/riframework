<?php

	error_reporting(0);
	require_once(dirname(__FILE__) . "/rif/loader/autoloader.php");
	autoloader::autoload();
	$riframework = new riframework();
	$riframework->framework();

?>