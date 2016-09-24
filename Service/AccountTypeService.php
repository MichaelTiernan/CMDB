<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/AccountTypeGateway.php';

class AccountTypeService extends Service{
    private $accountTypeGateway = NULL;
    public function __construct() {
        $this->accountTypeGateway = new AccountTypeGateway();
    }

    public function activate($id, $AdminName) {
        $this->accountTypeGateway->activate($id, $AdminName);
    }

    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->accountTypeGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->accountTypeGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->accountTypeGateway->selectById($id);
    }
    
    public function listAllTypes() {
        return $this->accountTypeGateway->getAllTypes();
    }
    
    public function create($type,$description, $AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->accountTypeGateway->create($type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function update($UUID,$type,$description,$AdminName){
        try {
            $this->validateTypeParams($type,$description);
            $this->accountTypeGateway->update($UUID, $type, $description, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function search($search) {
        return $this->accountTypeGateway->selectBySearch($search);
    }
    /**
     * 
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
        if ($this->accountTypeGateway->CheckDoubleEntry($type, $description)){
            $errors[] = 'The same Account Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
