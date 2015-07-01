<?php 
/**
 * PDO Helper that generates the queries easy
 */
class pdoHelper{
	
	/**
	 * [$queryStr description]
	 * @var string
	 */
	private $queryStr = "";

	/**
	 * [$fields description]
	 * @var array
	 */
	private $fields = array();
	

	/**
	 * [$hasWhereStr description]
	 * @var boolean
	 */
	private $hasWhereStr = false;

	/**
	 * [$needsAndOr description]
	 * @var boolean
	 */
	private $needsAndOr = false;

	/**
	 * [$firstOrder description]
	 * @var boolean
	 */
	private $firstOrder = true;

	/**
	 * [$firstGroupBy description]
	 * @var boolean
	 */
	private $firstGroupBy = true;
	

	public function __construct(rifLng $lng){
		$this->lng = $lng;
	}
	/**
	 * [setFieldRow description]
	 * @param integer
	 * @param string
	 * @param string
	 */
	public function setFieldRow($i, $field, $value){
		$field = str_replace(".","_",$field);
		$currentField = ":".$field;
		if($i>0) $currentField .= $i;
		if(isset($this->fields[$currentField])){
			$i++;
			return $this->setFieldRow($i,$field, $value);
		}else{
			$this->fields[$currentField] = $value;
			return array($currentField, $value);
		}
	}
	

	/**
	 * [whereGroup description]
	 * @param  string
	 * @param  array
	 * @return string
	 */
	public function whereGroup($andOr, $wheres){
		if(!is_string($andOr) || !is_array($wheres)){
			rifException::modelException(array(
				'message' => $this->lng->__("Invalid Group Where parameters.")
			));
		}
		$this->isWhereGroup = true;
		$query = "";

		if($this->hasWhereStr === false ) {
			$query .= "WHERE (";
			$this->hasWhereStr = true;
		}else{
			if($this->needsAndOr){
				$query .= " ".$andOr;
			}
			$query .= " (";
			$this->needsAndOr = false;
		}

		if($this->needsAndOr){
			$query .= " ".$andOr;
		}
		
		for($i = 0; $i < count($wheres); $i++){
			if($i === 0){
				$this->needsAndOr = false;
			}else{
				$this->needsAndOr = true;
			}
			$query .= $this->where($wheres[$i][0],$wheres[$i][1],$wheres[$i][2],$wheres[$i][3]);
		}

		return $query .= ") ";
	}

	/**
	 * [where description]
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public function where($andOr, $field, $condition, $value){
		if(!is_string($andOr) || !is_string($field) || !is_string($condition) || !is_string($value)){
			rifException::modelException(array(
				'message' => $this->lng->__("Invalid where parameters")
			));
		}
		$value = $this->setFieldRow(0,$field, $value);
		$query = "";

		if($this->hasWhereStr === false) {
			$query .= "WHERE ";
			$this->hasWhereStr = true;
			$this->needsAndOr = true;
		}else if($this->needsAndOr){
			$query .= $andOr." ";
		}

		$query .= $field." ".$condition." ".$value[0]." ";
		return $query;
	}
	
	/**
	 * [select description]
	 * @param  array
	 * @param  string
	 * @return string
	 */
	public function select($fields,$table){
		if(!is_array($fields) || !is_string($table)){
			rifException::modelException(array(
				'message' => $this->lng->__("Invalid fields for select")
			));
		}
		$fieldsStr = "";

		if($fields[0]=== "*" || $fields[0] === "all"){
			return "SELECT * FROM ".$table." ";
		}else{
			for($i = 0;$i<count($fields);$i++){
				if($i > 0) $fieldsStr .= ",";
				$fieldsStr .= $fields[$i]." ";
			}
			return "SELECT ".$fieldsStr." FROM ".$table." ";
		}
	}
	
	
	/**
	 * [join description]
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public function join($joinType, $joinTable, $joinField, $joinValue){
		if(!is_String($joinType) || !is_string($joinTable) || !is_string($joinField) || !is_string($joinValue)) {
			rifException::modelException(array(
				'message' => $this->lng->__("Invalid join parameters")
			));
		}
		return $joinType." ".$joinTable.' ON '.$joinField.' = '.$joinValue." ";
	}
	
	/**
	 * [order description]
	 * @param  string
	 * @param  string
	 * @return string
	 */
	public function order($orderField, $orderSort){
		if(!is_string($orderField) || !is_string($orderSort)){
			rifException::modelException(array(
				'message' => $this->lng->__("Invalid order parameters")
			));
		}
		$query = "";
		if($this->firstOrder){
			$this->firstOrder = false;
			$query .= "ORDER BY ";
		}else{
			$query  .= ", ";
		}
		$query  .= $orderField." ".$orderSort." ";
		return $query;
	}

	 /**
	  * [groupBy description]
	  * @param  string
	  * @return string
	  */
	public function groupBy($field){

		$query = "";
		if($this->firstGroupBy){
			$this->firstGroupBy = false;
			$query .= "GROUP BY ";
		}else{
			$query .= ",";
		}
		$query .= $field;
		return $query;
	}

	/**
	 * [getFields description]
	 * @return array
	 */
	public function getFields(){
		return $this->fields;
	}

	public function reset(){
		$this->queryStr = "";
		$this->fields = array();
		$this->hasWhereStr = false;
		$this->needsAndOr = false;
		$this->firstOrder = true;
		$this->firstGroupBy = true;
	}
}
?>