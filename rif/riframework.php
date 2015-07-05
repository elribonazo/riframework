<?
class riframework{

	public function __construct(){
		define("PATH",dirname(dirname(__FILE__)));
		define("LANG","es");
	}

	public function framework(){
		try{
			$rifCore = new rifCore();
			$rifLng = new rifLng(LANG);
			$rifRequest = new rifRequest($rifLng);
			$rifCore->_setLng($rifLng);
			$rifCore->_setConfig(new rifConfigLoader($rifLng));
			$rifCore->_setRouting(new rifRouting($rifLng, $rifRequest,$rifCore->getConfig()->getConfig()->getRoutes()));
			$rifCore->_setHooks(new rifHooks($rifLng));
			$rifCore->_setEvents(new rifEvent());
			if($rifCore->getRouting()->getError()){
				rifException::routingException(array(
					'message'=> $rifLng->__("Routing error : __err__",array("err"=>$rifCore->getRouting()->getErrorMsg()))
				));
			}
			$instance = new rifInstance($rifCore);
			$response = new rifResponse($rifCore, $instance);
		}catch(rifExceptionCallable $e){
			$error = new rifErr($e);
			if(isset($core->core['routing']) && $core->core['routing']->route['response'] === "json"){
				rifException::JsonResponseException(array(
					'message'=> $error->message
				));
			}else{
				print_R($error);
			}
		}
	}

	public function shell($argv){
		$lang = new rifLng(LANG);
		$config = new rifConfigLoader($lang);
		$shell = new rifShell(new rifCore($config,$lang));
		$shell->execute($argv);
	}
}
?>