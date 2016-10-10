<?php
require_once 'model/LoggerGateway.php';
class LoggerService {
    private $loggerGatteway = NULL;
    
    public function __construct() {
        $this->loggerGatteway = new LoggerGateway();
    }
    /**
     * this function will return all the log of a given object
     * @param string $table
     * @param mixed $uuid
     * @throws PDOException
     * @return array
     */
    public function listAllLogs($table, $uuid) {
        try {
            return $this->loggerGatteway->getLog($table, $uuid);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
}
