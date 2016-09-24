<?php
require_once 'Service.php';
require_once 'model/ApplicationGateway.php';

class ApplicationService extends Service{
    private $applicationGateway = NULL;
    
    public function __construct() {
        $this->applicationGateway = new ApplicationGateway();
    }

    public function activate($id, $AdminName) {
        
    }

    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->applicationGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $exc){
            throw $exc;
        }
    }

    public function getAll($order) {
        return $this->applicationGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->applicationGateway->selectById($id);
    }
    
    public function listAllApplications() {
        return $this->applicationGateway->getAllApplications();
    }

    public function search($search) {
        return $this->applicationGateway->selectBySearch($search);
    }
    
    public function createNewApplication($Name,$AdminName){
        try{
            $this->validateParameters($Name);
            $this->applicationGateway->create($Name,$AdminName);
        } catch (Exception $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function listAllAccounts($UUID) {
        return $this->applicationGateway->listAllAccounts($UUID);
    }
    /**
     * This will validate the givven parameters
     * @param String $Name The Name of the Application
     * @return 
     * @throws ValidationException
     */
    private function validateParameters($Name){
        $errors = array();
        if (empty($Name)) {
            $errors[] = 'Please enter a Name';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
