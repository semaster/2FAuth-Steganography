<?php if(!defined("IN_RULE")) die ("Oops");

class DB {
    private $connection;
    private static $instance;
    private $options = array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => true 
    );

    public function getConnection() {
        return $this->connection;
    } 

    public static function getInstance() {
        $dsn 	= "mysql:host=localhost;dbname=".DB_NAME.";";
        if (self::$instance === null) {
            self::$instance = new self($dsn, DB_USER, DB_PASSWORD);
        }
        return self::$instance;
    } 

    private function __construct($dsn, $user, $pass) {
        try {
            $this->connection = new PDO($dsn, $user, $pass, $this->options);
            $this->connection->exec("SET time_zone = '".date('P')."'");
        } catch(PDOException $e)	{
            $this->connection = 'Connection failed';
        }	
    }

    private function __desctruct() {
        $this->connection = NULL;
        self::$instance = NULL;
    }
}

?>
