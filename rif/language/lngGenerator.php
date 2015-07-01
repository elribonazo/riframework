<?php
class lngGenerator{

	public static function generate($core, $lang){
		$path = PATH;
		$translations = self::readFiles($path);
		$lng = new rifLng($lang);
		$poParser = $lng->getPoParser();
		$poParser->resetEntries();
		foreach($translations as $translation){
			$poParser->setEntry($translation->msgid,array(
				'msgid' => $translation->msgid,
				'msgstr' => $translation->msgstr
			),true);
		}
		$poParser->writeFile($path."/app/translations/".$lang.".po");
	}

	public static function readFiles($path){
		$exclude = array('.git');
		$filter = function ($file, $key, $iterator) use ($exclude) {
		    if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
		        return true;
		    }
		    return $file->isFile();
		};
		$innerIterator = new RecursiveDirectoryIterator(
		    $path,
		    RecursiveDirectoryIterator::SKIP_DOTS
		);
		$iterator = new RecursiveIteratorIterator(
		    new RecursiveCallbackFilterIterator($innerIterator, $filter)
		);
		$translations = array();
		foreach ($iterator as $filename => $fileInfo) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if($ext === "php"){
				$file = file_get_contents($filename);
				$matches = array();
				if(preg_match_all("/\_+e*\(\"([A-Za-z0-9\,\.\;\:\-\_\{\}\s\[\`\]\*\+\¡\¿\'\?\=\)\(\/\&\%\$\·\#\\@\!\|\\\ª\º\<\>\=]+)\"/x", $file, $matches)){
					if(count($matches) === 2){
						for($i=0;$i < count($matches[1]); $i++){
							$translation = new stdClass();
							$translation->msgid = $matches[1][$i];
							$translation->msgstr = $matches[1][$i];
							$translations[] =  $translation;
						}
					}
				}
			}
		}
		return $translations;
	}
}
?>