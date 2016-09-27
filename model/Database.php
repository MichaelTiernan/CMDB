<?php
/**
 * This class will make the connection to the Db
 * @author Hans Colman
 */
class Database
{
	/**
	 * The name of the Database
	 * @var string
	 */
    private static $dbName = 'CMDB' ;
    /**
     * The name of the host
     * @var string
     */
    private static $dbHost = 'localhost' ;
    /**
     * The name of the user
     * @var string
     */
    private static $dbUsername = 'root';
    /**
     * The Password
     * @var string
     */
    private static $dbUserPassword = '';
    /**
     * The connection
     * @var PDO
     */
    private static $cont  = null;
    
    public function __destruct() {
        self::$cont = null;
    }
    /**
     * Makes the connection to the dB
     * @return PDO
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