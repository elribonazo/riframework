<?php 
/**
 * Mysql connection Class
 */
class mysql{

	/**
	 * [$pdo description]
	 * @var PDO
	 */
	private $pdo;

	/**
	 * [__construct description]
	 */
	public function __construct(rifCore $rifCore){
		$config = $rifCore->core['config'];
		$this->lng = $rifCore->core['lng'];
		$db_host = $config->framework['database']['server'];
		$db_user = $config->framework['database']['user'];
		$db_name = $config->framework['database']['database'];
		$db_pass = $config->framework['database']['pass'];
		try{
			$this->pdo = new PDO('mysql:host='.$db_host.';dbname=' . $db_name, $db_user,$db_pass);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			rifException::instanceException(array(
				"message" => $this->lng->__("Database connection problem")
			));
		}
	}

	public function getLastInsertId(){
		return $this->pdo->lastInsertId();
	}

	/**
	 * [execute description]
	 * @param  array
	 * @return query
	 */
	public function execute(array $pdoQuery){
		try{
			$query = $this->pdo->prepare($pdoQuery["query"]);
			$query->execute($pdoQuery['fields']);
			return $query;
		} catch(PDOException $e) {
			rifException::instanceException(array(
				'message' => $e->getMessage()
			));
		}
	}
}
?>