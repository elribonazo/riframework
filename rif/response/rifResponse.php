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
		$rifRouting = $this->rifCore->getRouting()->getRoute();
		$frameworkMainConfig = $this->rifCore->getConfig();
		$response = (isset($rifRouting['response']))? $rifRouting['response'] : "html";
		if($response === "json"){
			$json = new rifJsonResponse();
			$json->processJson($this->rifInstance);
		}elseif($response === "html"){
			if($frameworkMainConfig->getTemplating() === "php"){
				$scripts = array();
				if(count($this->rifInstance->instance['globalVars'])>0){
					$pathGlobals = array(
						'rifPath' => $frameworkMainConfig->getUrl()
					);
					$jsContent = array(
						"vars" => array_merge($pathGlobals,$this->rifInstance->instance['globalVars']),
					);
					$scripts[] = new rifJs($jsContent);
				}
				new rifPhpResponse($this->rifCore, $this->rifInstance, $scripts);
			}
		}
	}
}
?>