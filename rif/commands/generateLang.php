<?php
/**
 * @param 0 lang
 */
class generateLang{
	public function run(){
		lngGenerator::generate($this->rifCore,$this->lang);
	}
}
?>