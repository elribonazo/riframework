<?php
/**
 * @param 0 lang
 */
class generateLang{
	public function run(rifCore $core){
		lngGenerator::generate($core,$this->lang);
	}
}
?>