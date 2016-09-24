<?php
require_once 'model/LoggerGateway.php';
class LoggerService {
    private $loggerGatteway = NULL;
    
    public function __construct() {
        $this->loggerGatteway = new LoggerGateway();
    }
    
    public function listAllLogs($table, $uuid) {
        try {
            return $this->loggerGatteway->getLog($table, $uuid);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
