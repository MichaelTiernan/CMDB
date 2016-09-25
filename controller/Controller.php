<?php
require_once 'Service/AccessService.php';
require_once 'LoggerController.php';

abstract class Controller {
    protected $accessService = NULL;
    protected $loggerController = NULL;
    /**
     * This function is the main function of this call
     * It will be usesd to call the other functions.
     */
    abstract function handleRequest();
    /**
     * This function will be used to Edit the given object
     */
    abstract function edit();
    /**
     * This function will be used to give the details of the given object
     */
    abstract function show();
    /**
     * This function will be used to activate the given object.
     */
    abstract function activate();
    /**
     * This function will be used to deactivate the given object.
     */
    abstract function delete();
    /***
     * This function will be used to create a given object
     */
    abstract function save();
    /**
     * This function will be used to get an overview of all objects
     */
    abstract function listAll();
    /**
     * This function will be used to search in all objects
     */
    abstract function search();
    /**
     * Constructor
     */
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
