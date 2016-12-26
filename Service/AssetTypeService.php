<?php
require_once 'Service.php';
require_once 'model/AssetTypeGateway.php';
class AssetTypeService extends Service{
    private $assetTypeGateway = NULL;
    
    public function __construct() {
        $this->assetTypeGateway = new AssetTypeGateway();
    }
	/**
	 * {@inheritDoc}
	 * @see Service::activate()
	 */
    public function activate($id, $AdminName) {
        try{
            $this->assetTypeGateway->activate($id, $AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
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
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function getAll($order) {
        return $this->assetTypeGateway->selectAll($order);
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function getByID($id) {
        return $this->assetTypeGateway->selectById($id);
    }
    /**
     * This function will create a new AssetType
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
     * @param string $AdminName The name of the person who did the creation
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
    /**
     * This function will update a given Asset Type
     * @param int $UUID The unique ID of the Asset Type
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
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
     * This function will validate the parameters
     * @param int $Category The category of the Asset Type
     * @param string $Vendor The name of the vendor of the Asset Type
     * @param string $Type The name of the asset type
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
