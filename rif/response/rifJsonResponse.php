<?
class rifJsonResponse{
	
	public function __construct(){
		
	}

	public function processJson(rifInstance $rifInstance){
		echo json_encode($rifInstance->instance['vars']);
	}
	
}
?>