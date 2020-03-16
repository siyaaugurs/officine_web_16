<?php
namespace config;

 class pdodatabase{
	 
    public $dbHost = "localhost";
    public $dbUsername = "officine";
    public $dbPassword = "Augurs@9848";
    public $dbName = "augurste_officine";
	
	public function connect(){
 		try {
          $conn = new \PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
         // set the PDO error mode to exception
          $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
          return $conn;
		  //$this->db = $conn;
		  echo "Connected successfully<br />"; 
            }
          catch(PDOException $e)
            {
             echo "Connection failed: " . $e->getMessage();
            }
	}
 }
?>