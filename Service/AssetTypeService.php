<?php
require_once 'Service.php';
require_once 'model/AssetTypeGateway.php';
class AssetTypeService extends Service{
    private $assetTypeGateway = NULL;
    
    public function __construct() {
        $this->assetTypeGateway = new AssetTypeGateway();
    }

    public function activate($id, $AdminName) {
        try{
            $this->assetTypeGateway->activate($id, $AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->assetTypeGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->assetTypeGateway->selectAll($order);
    }

    public function getByID($id) {
        return $this->assetTypeGateway->selectById($id);
    }
    /**
     * 
     * @param type $Category
     * @param type $Vendor
     * @param type $Type
     * @param type $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Category,$Vendor,$Type,$AdminName){
        try{
            $this->validateParameters($Category, $Vendor, $Type);
            $this->assetTypeGateway->create($Category, $Vendor, $Type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            print $e;
        }
    }
    
    public function update($UUID, $Category,$Vendor,$Type,$AdminName) {
        try{
            $this->validateParameters($Category, $Vendor, $Type);
            $this->assetTypeGateway->update($UUID,$Category, $Vendor, $Type, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * 
     * @return type
     */
    public function listAllCategories(){
        return $this->assetTypeGateway->getAllCategories();
    }
    
    public function search($search) {
        return $this->assetTypeGateway->selectBySearch($search);
    }
    /**
     * 
     * @param type $Category
     * @param type $Vendor
     * @param type $Type
     * @return type
     * @throws ValidationException
     */
    private function validateParameters($Category,$Vendor,$Type){
        $errors = array();
        if (empty($Category)) {
            $errors[] = 'Please select a Category';
        }
        if (empty($Vendor)){
            $errors[] = 'Please enter a Vendor';
        }
        if (empty($Type)){
            $errors[] = 'Please enter a Type';
        }
        if ($this->assetTypeGateway->CheckDoubleEntry($Category,$Vendor, $Type)){
            $errors[] = 'The same Asset Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
