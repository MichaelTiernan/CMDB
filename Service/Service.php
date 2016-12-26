<?php
require_once 'ValidationException.php';

abstract class Service {
    /**
     * This function will return all objects
     * @param string $order The name of the column the sorting will be done
     * @return array
     */
    abstract function getAll($order);
    /**
     * This function will return all details of the given object
     * @param mixed $id The unique ID of the Object
     * @return array
     */
    abstract function getByID($id);
    /**
     * This function will deactivate the given object
     * @param mixed $id The unique ID of the Object
     * @param string $reason The reason of deletion
     * @param string $AdminName The name of the person who is doing the deletion
     * @throws PDOException
     */
    abstract function delete($id,$reason,$AdminName);
    /**
     * This function will activate the given object 
     * @param mixed $id The unique ID of the Object
     * @param string $AdminName The name of the person who is doing the activation
     * @throws PDOException
     */
    abstract function activate($id, $AdminName);
    /**
     * This function will search in all object for a given string
     * @param string $search The search term
     * @return array
     */
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
