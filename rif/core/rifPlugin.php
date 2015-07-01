<?
class rifPlugin{

	public $hooks;

	public function addFilter($filter, $call){
		$this->hooks->add_filter($filter, $call);
	}
}
?>