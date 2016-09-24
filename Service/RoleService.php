<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/RoleGateway.php';

class RoleService extends Service{
    private $roleGateway = NULL;
    
    public function __construct() {
        $this->roleGateway = new RoleGateway();
    }

    public function activate($id, $AdminName) {
        $this->roleGateway->activate($id, $AdminName);
    }

    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->roleGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->roleGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->roleGateway->selectById($id);
    }
    
    public function create($Name,$Description,$Type,$AdminName){
        try {
            $this->validateParameters($Name, $Description, $Type);
            $this->roleGateway->create($Name,$Description,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function update($UUID,$Name,$Description,$Type,$AdminName){
        try {
            $this->validateParameters($Name, $Description, $Type);
            $this->roleGateway->update($UUID,$Name,$Description,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function search($search) {
        return $this->roleGateway->selectBySearch($search);
    }
    
    private function validateParameters($Name,$Description,$Type){
        $errors = array();
        if (empty($Name)) {
            $errors[] = 'Please enter a Name';
        }
        if (empty($Type)){
            $errors[] = 'Please select a Type';
        }
        if($this->roleGateway->CheckDoubleEntry($Type, $Description, $Name)){
            $errors[] = 'Role already exist in the application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
