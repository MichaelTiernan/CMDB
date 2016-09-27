<?php
class Database
{
    private static $dbName = 'CMDB' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';
     
    private static $cont  = null;
     
    public function __construct() {
       // die('Init function is not allowed');
    }
    /**
     * Makes the connection to the dB
     * @return type
     */ 
    protected static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont ){     
            try{
                self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
            }catch(PDOException $e){
                die($e->getMessage()); 
            }
       }
       return self::$cont;
    }
    /**
     * Disconects from dB
     */
    protected static function disconnect()
    {
        self::$cont = null;
    }
}
?>