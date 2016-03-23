<?php
require_once 'Database.php';
abstract  class Logger extends Database{
    abstract function read();
    //abstract function create();
    //abstract function update();
    //abstract function delete();
    abstract function details();
    
    protected function logCreate($Table,$UUID,$Value,$AdminName){
        
    }
    protected function logUpdate($Table,$UUID,$field,$oldValue,$NewValue,$AdminName){
        
    }
    protected function logDelete($Table,$UUID,$Value,$reason,$AdminName){
        
    }
    protected function logActivation($Table,$UUID,$Value,$AdminName){
        
    }
    private function doLog($Table,$UUID,$LogText){
        
    }
}
