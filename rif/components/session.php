<?
class session{
	
	public function __construct(){
		$this->create();
	}
	
	public function create(){
		if (session_status() == PHP_SESSION_NONE){
			session_start();
		}
	}

	public function set($var, $val){
		$this->create();
		$_SESSION[$var] = $val;
	}
	
	public function setArray($array){
		$this->create();
		foreach($array as $var => $val){
			$_SESSION[$var] = $val;
		}
	}

	public function destroy(){
		if (session_status() != PHP_SESSION_NONE){
			foreach($_SESSION as $var => $val){
				unset($_SESSION[$var]);
			}
			session_destroy();
		}
	}
}
?>