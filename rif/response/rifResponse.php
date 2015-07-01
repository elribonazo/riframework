<?
class rifResponse{

	public $rifCore;
	public $rifInstance;

	public function __construct(rifCore $rifCore, rifInstance $rifInstance){
		$this->rifCore = $rifCore;
		$this->rifInstance = $rifInstance;
		$this->processResponse();
	}

	private function processResponse(){
		$rifRouting = $this->rifCore->core['routing'];
		$rifConfig = $this->rifCore->core['config'];
		$frameworkMainConfig = $rifConfig->framework['main'];
		$response = (isset($rifRouting->route['response']))? $rifRouting->route['response'] : "html";
		if($response === "json"){
			$json = new rifJsonResponse();
			$json->processJson($this->rifInstance);
		}elseif($response === "html"){
			if($frameworkMainConfig['templating'] === "php"){
				$scripts = array();
				if(count($this->rifInstance->instance['globalVars'])>0){
					$pathGlobals = array(
						'rifPath' => $this->rifCore->core['config']->framework['main']['url']
					);
					$jsContent = array(
						"vars" => array_merge($pathGlobals,$this->rifInstance->instance['globalVars']),
					);
					$scripts[] = new rifJs($this->rifCore,$jsContent);
				}
				new rifPhpResponse($this->rifCore, $this->rifInstance, $scripts);
			}
		}
	}
}
?>