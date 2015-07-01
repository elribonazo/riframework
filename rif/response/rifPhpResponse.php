<?
class rifPhpResponse{

	private $path;
	private $event;
	private $instance = array();

	public function __construct(rifCore $rifCore, rifInstance $rifInstance, $scripts){
		$theme = $rifCore->core['config']->framework['main']['theme'];
		$this->lng = $rifCore->core['lng'];

		$this->event = $rifCore->core['event'];
		$this->instance = $rifInstance;
		$route = $rifCore->core['routing']->route;
		$templatePath = dirname(dirname(dirname(__FILE__)))."/app/template/".$theme."/";

		$this->path = $templatePath;
		$controllerPath = str_replace("Controller","",$route['controller']);
		$templateFile = $templatePath.$controllerPath."/".$route['action'].".php";
		$this->validateResponse($templatePath, $templateFile);
		$this->loadFile($templateFile, $rifCore, $scripts);
	}

	public function validateResponse($templatePath, $templateFile){
		if(!file_exists($templatePath)){
			rifException::phpResponse(array(
				'message'=> $this->lng->__("The template folder __file__ does not exist.",array("file"=> $templateFile))
			));
		}
		if(!file_exists($templateFile)){
			rifException::phpResponse(array(
				'message'=> $this->lng->__("The template file __file__ does not exist.",array("file"=> $templateFile))
			));
		}
	}

	public function loadFile($file, rifCore $rifCore, $scripts){
		$this->event->on("rif.styles", function($args = array()){
			if(is_array($args)){
				foreach($args as $css){
					echo '<link href="'.$css.'" rel="stylesheet">'."\n";
				}
			}
		});
		$this->event->on("rif.scripts", function($args = array()){
			if(is_array($args)){
				foreach($args as $js){
					if($js instanceof rifJs){
						echo '<script type="text/javascript">'.$js->render().'</script>'."\n";
					}else{
						echo '<script type="text/javascript" src="'.$js.'"></script>'."\n";
					}
					
				}
			}
		});
		$template = new rifPhpHelper($this->path, $this->instance, $rifCore, $this->event, $scripts);
		if(file_exists($this->path."autoload.php")){
			include($this->path."autoload.php");
		}
		include($file);
	}	
}
?>