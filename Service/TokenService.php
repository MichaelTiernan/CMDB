<?php
require_once 'Service.php';
require_once 'model/TokenGateway.php';
class TokenService extends Service{
    private $tokenModel = NULL;
    public function __construct() {
        $this->tokenModel = new TokenGateway();
    }

    public function activate($id, $AdminName) {
        try{
            $this->tokenModel->activate($id, $AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->tokenModel->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->tokenModel->selectAll($order);
    }

    public function getByID($id) {
        return $this->tokenModel->selectById($id);
    }

    public function search($search) {
        return $this->tokenModel->selectBySearch($search);
    }
    
    public function create($assetTag, $serialNumber, $type,$AdminName) {
        try{
            $this->validateParameters($assetTag, $serialNumber, $type);
            $this->tokenModel->create($assetTag, $serialNumber, $type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function update($AssetTag, $SerialNumber, $Type, $AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, TRUE);
            $this->tokenModel->update($AssetTag,$SerialNumber,$Type,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }  catch (PDOException $e){
            throw $e;
        }
    }

    public function listAllTypes(){
        return $this->tokenModel->listAllTokenCategories();
    }
    
    public function listOfAssignedIdentities($assetTag){
        return $this->tokenModel->listOfAssignedIdentities($assetTag);
    }
    /**
     * 
     * @param type $assetTag
     * @param type $serialNumber
     * @param type $type
     * @param boolean $update
     * @return type
     * @throws ValidationException
     */
    private function validateParameters($assetTag, $serialNumber, $type, $update = FALSE){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please select a Type';
        }
        if (empty($assetTag)) {
            $errors[] = 'Please enter a assetTag';
        }
        if (empty($serialNumber)) {
            $errors[] = 'Please enter a serial number';
        }
        if (!$update){
            if (!$this->tokenModel->isAssetTagUnique($assetTag)){
                $errors[] = 'Asset is not unique';
            }
        }
        if (!$this->tokenModel->isSerialUnique($serialNumber)){
            $errors[] = 'SerialNumber is not unique';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
