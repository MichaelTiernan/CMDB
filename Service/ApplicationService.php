<?php
require_once 'Service.php';
require_once 'model/ApplicationGateway.php';

class ApplicationService extends Service{
    private $applicationGateway = NULL;
    
    public function __construct() {
        $this->applicationGateway = new ApplicationGateway();
    }

    public function activate($id, $AdminName) {
    	try{
        	$this->applicationGateway->activate($id, $AdminName);
        } catch (PDOException $exc){
        	throw $exc;
        }
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
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function listAllAccounts($UUID) {
        return $this->applicationGateway->listAllAccounts($UUID);
    }
    
    public function edit($UUID,$Name,$AdminName){
    	try {
    		$this->validateParameters($Name,$UUID);
    		$this->applicationGateway->update($UUID,$Name,$AdminName);
    	} catch (ValidationException $ex) {
    		throw $ex;
    	} catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This will validate the givven parameters
     * @param String $Name The Name of the Application
     * @return 
     * @throws ValidationException
     */
    private function validateParameters($Name, $UUID = 0){
        $errors = array();
        if (empty($Name)) {
            $errors[] = 'Please enter a Name';
        }
        if ($UUID >0 and $this->applicationGateway->alreadyExist($Name,$UUID)){
        	$errors[] = 'This application already exist';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
