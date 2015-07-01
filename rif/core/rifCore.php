<?
class rifCore{
	public $core = array();
	public function __construct(rifConfig $config,rifLng $lng, $routing = null,$hooks = null,$events = null){
		$this->core['config']=$config;
		$this->core['routing']=$routing;
		$this->core['hooks']=$hooks;
		$this->core['event']=$events;
		$this->core['lng'] = $lng;
	}
}
?>