<?
class rifTwigResponse{
	public function __construct(rifCore $core, rifInstance $rifInstance){
		echo json_encode($rifInstance);
	}
}
?>