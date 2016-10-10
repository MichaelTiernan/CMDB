<?php
require_once ($_SERVER["DOCUMENT_ROOT"] .'/CMDB/Service/LoggerService.php');
class LoggerController {
    private $loggerService = NULL;
    
    public function __construct() {
        $this->loggerService = new LoggerService();
    }
    /**
     * This function will return all Logs from a given object
     * @param string $table The table from where the log should come
     * @param mixed $uuid The Unique ID of the Object
     * @throws PDOException
     * @return array
     */
    public function listAllLogs($table, $uuid) {
        try {
            return $this->loggerService->listAllLogs($table, $uuid);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
}
