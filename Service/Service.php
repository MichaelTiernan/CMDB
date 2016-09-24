<?php
require_once 'ValidationException.php';

abstract class Service {
    
    abstract function getAll($order);
    abstract function getByID($id);
    abstract function delete($id,$reason,$AdminName);
    abstract function activate($id, $AdminName);
    abstract function search($search);
    /**
     * This function will Validate the Delete Parameter
     * @param String $reason
     * @return type
     * @throws ValidationException
     */
    protected function validateDeleteParams($reason){
        $errors = array();
        if (empty($reason)) {
            $errors[] = 'Please enter Reason';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }
}
