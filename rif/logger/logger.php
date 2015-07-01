<?
class logger {

	public static function error($msg){
		SELF::addLogEntry("ERROR ::: " . $msg->errorType ." - " . $msg->message);
	}

	public static function debug($msg){
		SELF::addLogEntry("DEBUG ::: "  . $msg->errorType ." - " . $msg->message);
	}

	public static function info($msg){
		SELF::addLogEntry("INFO ::: " . $msg->errorType ." - " . $msg->message);
	}

	private static function addLogEntry($msg){
		error_log($msg);
	}
}
?>