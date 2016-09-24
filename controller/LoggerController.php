<?php
require_once ($_SERVER["DOCUMENT_ROOT"] .'/CMDB/Service/LoggerService.php');
class LoggerController {
    private $loggerService = NULL;
    
    public function __construct() {
        $this->loggerService = new LoggerService();
    }
    
    public function listAllLogs($table, $uuid) {
        try {
            return $this->loggerService->listAllLogs($table, $uuid);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
