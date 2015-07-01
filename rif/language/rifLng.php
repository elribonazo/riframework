<?
class rifLng{

	private $config;
	private $langCode;
	private $poParser;
	private $fileHandler;
	private $entries = array();

	public function __construct($lang = "es"){
		$this->langCode = $lang;
		$this->loadLanguage();
	}

	private function loadLanguage(){
		$this->fileHandler = new FileHandler(PATH."/app/translations/".$this->langCode.".po");
		$this->poParser = new PoParser($this->fileHandler);
		$this->setEntries($this->langCode,$this->poParser->parse());
	}

	private function replaceVariables($string, $vars){
		foreach($vars as $var => $val){
			$string = preg_replace("/\\_\\_".$var."\\_\\_/", $val, $string);
		}
		return $string;
	}

	public function __($string,$vars = array()){
		$translated = (isset($this->entries[$this->langCode][$string])) ? $this->entries[$this->langCode][$string]:$string;
		return $this->replaceVariables(isset($translated['msgstr']) ? $translated['msgstr'][0] : $string, $vars);
	}
	public function _e($string,$vars = array()){
		echo $this->__($string, $vars);
	}
	public function setPoParser(PoParser $parser){
		$this->poParser = $parser;
	}
	public function getPoParser(){
		return $this->poParser;
	}
	public function getFileHandler(){
		return $this->fileHandler;
	}
	public function setFileHandler(FileHandler $file){
		$this->fileHandler = $file;
	}
	public function setLangCode($langCode){
		$this->langCode = $langCode;
	}
	public function getLangCode($langCode){
		return $this->langCode;
	}
	public function getLangEntries($lang){
		return $this->entries[$lang];
	}
	public function getEntries(){
		return $this->entries;
	}
	public function setEntries($lang, $entries){
		$this->entries[$lang] = $entries;
	}
}
?>