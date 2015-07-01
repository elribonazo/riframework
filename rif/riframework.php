<?
class riframework{

	public function __construct(){
		define("PATH",dirname(dirname(__FILE__)));
		define("LANG","es");
	}

	public function framework(){
		try{
			$rifLng = new rifLng(LANG);
			$config = new rifConfig($rifLng);
			$hooks = new hooks($rifLng);
			$request = new rifRequest($rifLng);

			

			$routing = new rifRouting($rifLng, $request, $config->routes);


			$events = new rifEvent();
			if($routing->hasError()){
				rifException::routingException(array(
					'message'=> $rifLng->__("Routing error : __err__",array("err"=>$routing->getError()))
				));
			}
			$core = new rifCore($config,$rifLng, $routing, $hooks, $events);




			$instance = new rifInstance($core);
			$response = new rifResponse($core, $instance);
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
		$config = new rifConfig($lang);
		$shell = new rifShell(new rifCore($config,$lang));
		$shell->execute($argv);
	}
}
?>