<?php
require_once 'Service/AccessService.php';
require_once 'Controller.php';

class MenuController{
    private $accessService = NULL;
    
    public function __construct() {
        $this->accessService = new AccessService();
    }
    /**
     * This is the main class of the controller
     */
    public function handleRequest(){
        $this->listMenu();
    }
    /**
     * This function will return the menu on the top level.
     */
    private function listMenu(){
        $Level = $_SESSION["Level"];
//        $Action = "Read";
        $FirstMenu = $this->accessService->getFirstLevel();
        include 'view/menu.php';
    }
}
