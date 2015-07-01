<?
class rifJs{
	
	private $rifCore;
	private $global = "rif";
	private $content = "";

	public function __construct(rifCore $rifCore, $globals){
		$this->rifCore = $rifCore;
		$this->content .= "\n"."(function(".$this->global."){"."\n";
		$this->content .= "   \"use strict\";"."\n";
		if(isset($globals['vars'])){
			$this->content .= $this->getVarGlobals($globals['vars']);
		}
		$this->content .= "}(this));"."\n";
	}

	private function getVarGlobals($vars){
		$content = "";
		foreach($vars as $var => $val){
			$content .= $this->getVarLine($var, $val);
		}
		return $content;
	}



	private function getVarLine($var, $val){
		if(is_array($val)){
			return "   ".$this->global.".".$var."=".json_encode($val).";"."\n";
		}else{
			return  "   ".$this->global.".".$var."='".$val."'".";"."\n";
		}
	}

	public function render(){
		return $this->content;
	}

}

?>