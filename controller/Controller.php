<?php
require_once 'Service/AccessService.php';
require_once 'LoggerController.php';

abstract class Controller {
    protected $accessService = NULL;
    protected $loggerController = NULL;
    
    abstract function handleRequest();
    abstract function edit();
    abstract function show();
    abstract function activate();
    abstract function delete();
    abstract function save();
    abstract function listAll();
    abstract function search();
    
    public function __construct() {
        $this->accessService = new AccessService();
        $this->loggerController = new LoggerController();
    }
    /**
     * This function will show an given error
     * @param string $title The title of the error
     * @param string $message The message
     */
    public function showError($title, $message) {
        include 'view/error.php';
    }
    /**
     * This function will redirect to the the given location
     * @param string $location
     */
    public function redirect($location) {
        header('Location: '.$location);
    }
}
