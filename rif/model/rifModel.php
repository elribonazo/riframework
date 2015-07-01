<?
/**
 * Rif parent model
 */
class rifModel extends mysql{

	/**
	 * [$model description]
	 * @var model
	 */
	public $model;

	/**
	 * [$modelAnotations description]
	 * @var array
	 */
	private $modelAnotations;

	/**
	 * [$query description]
	 * @var array
	 */
	private $query = array(
		'query' => "",
		'fields' => array()
	);

	private $config;

	//private $relations = array();

	/**
	 * [__construct description]
	 * @param string
	 */
	public function __construct(rifCore $rifCore, $model){
		$this->model = $model;
		$this->lng = $rifCore->core['lng'];
		parent::__construct($rifCore);
		$this->getModelClassAnnotations();
		$this->pdoHelper = new pdoHelper($this->lng);		
		$this->config = $rifCore->core['config'];
	}

	/**
	 * [getModelClassAnnotations description]
	 * @return [type]
	 */
	public function getModelClassAnnotations(){
	    $model = new ReflectionClass($this->model);
	    $this->modelAnotations['model'] = rifAnotations::getAnnotations($model->getDocComment());
	    $properties   = $model->getProperties();
	    $anotations = array();
		foreach ($properties as $property) {
			$anotations[$property->getName()] = rifAnotations::getAnnotations($property->getDocComment());
		}
		$this->modelAnotations['properties'] = $anotations;
	}

	/**
	 * [select description]
	 * @param  array
	 * @return [type]
	 */
	public function select($fields){
		if(!is_array($fields) || count($fields)<=0) throw new rifException("Invalid select query");
		$this->query['query'] .= $this->pdoHelper->select($fields, $this->getTable());
	}

	/**
	 * [where description]
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 */
	public function where($andOr, $field, $condition,$value){
		$this->query['query'] .= $this->pdoHelper->where($andOr, $field, $condition, $value);
	}

	/**
	 * [whereGroup description]
	 * @param  string
	 * @param  array
	 */
	public function whereGroup($andOr, $wheres){
		$this->query['query'] .= $this->pdoHelper->whereGroup($andOr, $wheres);
	}

	/**
	 * [join description]
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 */
	public function join($joinType,$joinTable,$joinField,$joinValue){
		$this->query['query'] .= $this->pdoHelper->join($joinType, $joinTable, $joinField, $joinValue);
	}

	/**
	 * [deleteBy description]
	 * @param  boolean
	 * @param  string
	 */
	public function deleteBy($property = false, $condition = "="){
		if($property === false){
			rifException::modelException(array(
				'message'=> $this->lng->__("Set a valid property to delete.")
			));
		}

		$fields = array();
		$query = "DELETE FROM ".$this->getTable()." WHERE ";

		if(empty($this->model->$property) or !isset($this->model->$property)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Undefined property __prop__",array("prop"=>$property))
			));
		}else{
			$fields[":".$property] = $this->model->$property;
			$query .= $property." ".$condition." :".$property;	
		}

		if(count($fields)<=0){
			rifException::modelException(array(
				'message'=> $this->lng->__("No rows selected")
			));
		}
		$pdoQuery = array(
			'query' => $query,
			'fields' => $fields
		);

		$this->reset();
		return $this->execute($pdoQuery);
	}

	/**
	 * [updateBy description]
	 * @param  boolean
	 * @param  string
	 */
	public function updateBy($property = false, $condition = "="){
		if($property === false || empty($this->model->$property)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Set a valid property to update.")
			));
		}
		$fields = array();
		$query = "UPDATE ".$this->getTable()." SET ";
		$i = 0;
		foreach($this->modelAnotations['properties'] as $propertyA => $anotation){
			if(!isset($anotation['primary_key']) && !isset($anotation['auto_increment'])){
				if(!empty($this->model->$propertyA) && $propertyA !== $property){
					$fields[":".$propertyA] = $this->model->$propertyA;
					if($i > 0){
						$query .= ",";
					}
					$fields[":".$propertyA] = $this->model->$propertyA;
					$query .= $propertyA." = :".$propertyA;
					$i++;
				}
			}
		}

		$where = " WHERE ".$property." ".$condition." :".$property;
		$fields[":".$property] = $this->model->$property;
		$query .= " ".$where;
		if(count($fields)<= 1){
			rifException::modelException(array(
				'message'=> $this->lng->__("No fields set for update")
			));
		}
		$pdoQuery = array(
			'query' => $query,
			'fields' => $fields
		);
		$executed = $this->execute($pdoQuery);
		$this->reset();
		return $executed;
	}

	/**
	 * [insert description]
	 * @return [type]
	 */
	public function insert(){

		$fields = array();
		$query = "INSERT INTO ".$this->getTable()." ";
		$queryFields = "";
		$queryValues = "";
		$i = 0;
		foreach($this->modelAnotations['properties'] as $property => $anotation){
			if(!isset($anotation['primary_key']) && !isset($anotation['auto_increment'])){
				if(isset($anotation['required']) 
					&& $anotation['required'] === true 
					&& (empty($this->model->$property) && $this->model->$property!==0)){
					rifException::modelException(array(
						'message'=> $this->lng->__("The field __prop__ is required. ",array("prop"=>$property))
					));
				}
				$fields[":".$property] = $this->model->$property;
				if($i === 0){
					$queryFields .= "(";
					$queryValues .= "(";
				}else if($i > 0) {
					$queryFields .= ",";
					$queryValues .= ",";
				}
				$queryFields .= $property;
				$queryValues .= ":".$property;
				$i++;
			}
		}
		$queryFields .= ")";
		$queryValues .= ")";

		$query .= $queryFields. " VALUES ".$queryValues;

		$this->query['query'] = $query;
		$this->query['fields'] = $fields;

		$executed = $this->execute($this->query);
		$this->reset();

		
		return $executed;
	}



	/**
	 * [getTable description]
	 * @return [type]
	 */
	public function getTable(){
		if(!isset($this->modelAnotations['model']['table']) || empty($this->modelAnotations['model']['table'])  ){
			rifException::modelException(array(
				'message'=> $this->lng->__("Could not get the model table")
			));
		}
		return $this->modelAnotations['model']['table'];
	}
	/**
	 * [set description]
	 * @param string
	 * @param string
	 */
	public function set($property, $value){
		if(!property_exists($this->model, $property)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ does not exist",array("prop"=>$property))
			));
		}
		if(!isset($this->modelAnotations['properties'][$property]['type'])){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ doesn't have a declared type.",array("prop"=>$property))
			));
		}
		$type = $this->modelAnotations['properties'][$property]['type'];

		if($type === "integer" && !is_int((int) $value)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ is not a __type__",array("prop"=>$property,"type"=>$type))
			));
		}else if($type === "double" && !is_double((double) $value)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ is not a __type__",array("prop"=>$property,"type"=>$type))
			));
		}else if($type === "bool" && !is_bool((bool) $value)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ is not a __type__",array("prop"=>$property,"type"=>$type))
			));
		}else if($type === "float" && !is_float((float) $value)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ is not a __type__",array("prop"=>$property,"type"=>$type))
			));
		}else if($type === "string" && !is_string((string) $value)){
			rifException::modelException(array(
				'message'=> $this->lng->__("Property __prop__ is not a __type__",array("prop"=>$property,"type"=>$type))
			));
		}

		$this->model->$property = $value;
	}

	/**
	 * [printQuery description]
	 * @return query
	 */
	public function printQuery(){
		return $this->query;
	}

	/**
	 * [buildPdoQuery description]
	 * @return [type]
	 */
	public function buildPdoQuery(){
		$this->query['fields'] = $this->pdoHelper->getFields();
	}

	/**
	 * [run description]
	 * @return [type]
	 */
	public function run(){
		$this->buildPdoQuery();
		$executed = $this->execute($this->query);
		$this->reset();
		return $executed;
	}

	/**
	 * [reset description]
	 * @return [type]
	 */
	public function reset(){
		foreach($this->modelAnotations['properties'] as $property => $anotation){
			unset($this->model->$property);
		}
		$this->pdoHelper->reset();
		$this->query = array("query"=>"", "fields"=>array());
	}
}
?>