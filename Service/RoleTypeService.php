<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/RoleTypeGateway.php';

class RoleTypeService extends Service{
    private $roleTypeGateway =NULL;
    
    public function __construct() {
        $this->roleTypeGateway = new RoleTypeGateway();
    }

    public function activate($id, $AdminName) {
        $this->roleTypeGateway->activate($id, $AdminName);
    }

    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->roleTypeGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->roleTypeGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->roleTypeGateway->selectById($id);
    }
    
    public function create($type,$description, $AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->roleTypeGateway->create($type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } 
    }
    
    public function update($UUID,$type,$description,$AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->roleTypeGateway->update($UUID, $type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    /**
     * This function will return all active RoleTypes
     * @return array
     */
    public function listAllType() {
        return $this->roleTypeGateway->getAllTypes();
    }
    
    public function search($search) {
        return $this->roleTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters and throw an error when not all required fields are filled in
     * @param type $type
     * @param type $description
     * @return type
     * @throws ValidationException
     */
    private function validateTypeParams($type,$description){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please enter a Type';
        }
        if (empty($description)){
            $errors[] = 'Please enter a Description';
        }
        if ($this->roleTypeGateway->CheckDoubleEntry($type, $description)){
            $errors[] = 'The same Identity Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}