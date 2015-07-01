<?php 
/**
 * rifAutoloader class
 */
class autoloader{
	public static function autoload(){
		spl_autoload_register(function ($class) {
			if(!class_exists($class)){
				$mainPath = dirname(dirname(dirname(__FILE__)));
				$paths = array(
					$mainPath."/rif",
					$mainPath."/rif/logger",
					$mainPath."/rif/exception",
					$mainPath."/rif/configuration",
					$mainPath."/rif/controller",
					$mainPath."/rif/database",
					$mainPath."/rif/model",
					$mainPath."/rif/system/controllers",
					$mainPath."/rif/system/models",
					$mainPath."/rif/core",
					$mainPath."/rif/request",
					$mainPath."/rif/commands",
					$mainPath."/rif/plugins",
					$mainPath."/rif/response",
					$mainPath."/rif/instance",
					$mainPath."/rif/routing",
					$mainPath."/rif/components",
					$mainPath."/rif/language",
					$mainPath."/app/commands",
					$mainPath."/app/plugins",
					$mainPath."/app/controllers",
					$mainPath."/app/models",
					$mainPath."/app/utils",
					$mainPath."/app/components"
				);
				foreach($paths as $path){
					if(file_exists($path."/".$class.".php")){
						require($path."/".$class.".php");
					}
				}
			}
		});
	}
}
?>