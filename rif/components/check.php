<?
class check{

	public function isAjax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			return true;
		}else{
			return false;
		}
	}
	
	public function isPost(){
		return (isset($_POST)) ? true : false;
	}
}
?>