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
		$config = $rifCore->getConfig()->getConfig();
		$this->lng = $rifCore->getLng();
		$db_host = $config->getHost();
		$db_user = $config->getUser();
		$db_name = $config->getDatabase();
		$db_pass = $config->getPassword();
		try{
			$connection = 'mysql:host='.$db_host.';dbname=' . $db_name;
			$this->pdo = new PDO($connection,$db_user,$db_pass);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			rifException::instanceException(array(
				"message" => $this->lng->__("Database connection problem : __error__",array("error"=>$e->getMessage()))
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