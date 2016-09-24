<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/KensingtonGateway.php';
class KensingtonService extends Service{
    private $kensingtonGateway = null;


    public function __construct() {
        $this->kensingtonGateway = new KensingtonGateway();
    }

    public function activate($id, $AdminName) {
        
    }

    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->kensingtonGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    
    public function add($Type,$Serial,$NrKeys,$hasLock,$AdminName){
        try {
            $this->validateParams($Type,$Serial,$NrKeys,$hasLock);
            $this->kensingtonGateway->create($Type,$Serial,$NrKeys,$hasLock,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function edit($UUID,$Type,$Serial,$NrKeys,$hasLock,$AdminName) {
        try {
            $this->validateParams($Type, $Serial, $NrKeys, $hasLock, $UUID);
            $this->kensingtonGateway->update($UUID, $Type, $Serial, $NrKeys, $hasLock, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw  $e;
        }
    }
    
    public function getAll($order) {
        return $this->kensingtonGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->kensingtonGateway->selectById($id);
    }

    public function search($search) {
        return $this->kensingtonGateway->selectBySearch($search);
    }
    /**
     * This function will return all active Token Types
     * @return Array
     */
    public function listAllTypes(){
        return $this->kensingtonGateway->listAllTypes();
    }
    
    public function listAssets($UUID) {
        return $this->kensingtonGateway->GetAssetInfo($UUID);
    }
    
    private function validateParams($Type,$Serial,$NrKeys,$hasLock, $UUID = 0) {
        $errors = array();
        if (empty($Type)){
            $errors[] = 'Please select a Type';
        }
        if (empty($Serial)){
            $errors[] = 'Please enter a Serial Number';
        }
        if (!isset($NrKeys)){
            $errors[] = 'Please enter a Amount of Keys';
        }
        if (!isset($hasLock)){
            $errors[] = 'Please select if the key has a lock';
        }
        if (isset($NrKeys) and !is_numeric($NrKeys)){
            $errors[] = 'Please enter only number in the amount of keys';
        }
        if (!empty($Serial) and !empty($Type) and $this->kensingtonGateway->isUnique($Type, $Serial, $UUID)){
            $errors[] = 'The same key alread exsist';
        }
        if (empty($errors)) {
            return;
        }
        throw new ValidationException($errors);
    }
}
