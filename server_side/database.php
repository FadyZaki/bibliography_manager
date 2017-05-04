<?php

class Db {
    
    protected $con;
    //Setting the database parameters 
    private $host = "eu-cdbr-azure-north-e.cloudapp.net";
    private $user = "bd80b4620f7590";
    private $pwd = "f82b669d";
    private $db = "acsm_ff9a877681889df";
    
    //Creates a PDO conection & sets error mode to exceptions
    public function __construct(){
    
        try { 
            $this->con = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd); 
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } 
        catch(PDOException $e) { 
            echo $e->getMessage();
        }
        
    }
    
    //sets the datab to null
    public function disconnect() {
        
        $this->con = null;
        
    }

    //Create table in the database
    public function createTable($sql) {
        try {
            $this->con->query($sql);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //Drop table from the database
    public function dropTable($tableName) {
        try {
            $sql = "DROP TABLE $tableName;";
            $this->con->query($sql);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function executeInsert($query, $queryParams) {
        try {
            $q = $this -> con -> prepare($query);    
            $queryParams = array_combine(range(1, count($queryParams)), array_values($queryParams));
            foreach ($queryParams as $key => &$value) {
                if(!empty($value)){
                    $q -> bindParam($key, $value);
                }
                else {
                    $myNull = null;
                    $q -> bindParam($key, $myNull, PDO::PARAM_NULL);
                }
            }
            return $q -> execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function executeUpdate($query, $queryParams) {
        try {
            $q = $this -> con -> prepare($query);    
            $queryParams = array_combine(range(1, count($queryParams)), array_values($queryParams));
            $currentKey = 0;
            foreach ($queryParams as $key => &$value) {
                if(is_array($value)){
                   foreach ($value as $v)
                   {
                    $currentKey = $currentKey + 1;
                    $q -> bindParam($currentKey, $v); 
                   }
                }
                else {
                   $currentKey = $key;
                   $q -> bindParam($currentKey, $value); 
                }
            }
            return $q -> execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function executeDelete($query, $queryParams) {
        try {
            $q = $this -> con -> prepare($query);    
            $queryParams = array_combine(range(1, count($queryParams)), array_values($queryParams));
            $currentKey = 0;
            foreach ($queryParams as $key => &$value) {
                if(is_array($value)){
                   foreach ($value as $v)
                   {
                    $currentKey = $currentKey + 1;
                    $q -> bindParam($currentKey, $v); 
                   }
                }
                else {
                   $currentKey = $key;
                   $q -> bindParam($currentKey, $value); 
                }
            }
            return $q -> execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function executeSelectOne($query, $queryParams) {
        try {
            $q = $this -> con -> prepare($query);      
            $queryParams = array_combine(range(1, count($queryParams)), array_values($queryParams));
            foreach ($queryParams as $key => &$value) {
                $q -> bindParam($key, $value);
            }
            $q -> execute();
            return $q -> fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function executeSelectAll($query, $queryParams) {
        try {
            $q = $this -> con -> prepare($query);      
            $queryParams = array_combine(range(1, count($queryParams)), array_values($queryParams));
            foreach ($queryParams as $key => &$value) {
                $q -> bindParam($key, $value);
            }
            $q -> execute();
            return $q -> fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }



    
}
?>