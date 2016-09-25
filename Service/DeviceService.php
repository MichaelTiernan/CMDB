<?php
require_once 'Service.php';
require_once 'model/DeviceGateway.php';
class DeviceService extends Service{
    private $deviceGateway = NULL;
    private $category = NULL;


    public function __construct() {
        $this->deviceGateway = new DeviceGateway();
    }

    public function setCategory($category) {
        $this->category = $category;
        $this->deviceGateway->setCategory($category);
    }

    public function activate($id, $AdminName) {
        try {
            $this->deviceGateway->activate($id,$AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function delete($id, $reason, $AdminName) {
        try {
            $this->validateDeleteParams($reason);
            $this->deviceGateway->delete($id, $reason, $AdminName);
        } catch (ValidationException $ex) {
           throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }

    public function getAll($order) {
        return $this->deviceGateway->selectAll($order);
    }

    public function getByID($id) {
        try{
            return $this->deviceGateway->selectById($id);
        }  catch (PDOException $e){
            print $e->getMessage();
        }
    }
    
    public function create($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC);
            $this->deviceGateway->create($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
        }  catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    public function update ($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName){
        try{
            $this->validateParameters($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC);
            $this->deviceGateway->update($AssetTag, $SerialNumber, $Type, $RAM, $IP, $Name, $MAC, $AdminName);
        }  catch (ValidationException $ex){
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    public function listAllTypes($Category){
        return $this->deviceGateway->listAllTypes($Category);
    }
    
    public function listAllRams(){
        return $this->deviceGateway->listAllRams();
    }

    public function search($search) {
        return $this->deviceGateway->selectBySearch($search);
    }
    /**
     * 
     * @param type $AssetTag
     * @param type $SerialNumber
     * @param type $Type
     * @param type $RAM
     * @param type $IP
     * @param type $Name
     * @param type $MAC
     * @return type
     * @throws ValidationException
     */
    private function validateParameters($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC){
        $errors = array();
        if (empty($AssetTag)) {
            $errors[] = 'Please enter AssetTag';
        }
        if (empty($SerialNumber)) {
            $errors[] = 'Please enter SerialNumber';
        }
        if (empty($Type)) {
            $errors[] = 'Please select a type';
        }
        if (!$this->tokenModel->isAssetTagUnique($AssetTag)){
            $errors[] = 'Asset is not unique';
        }
        //TODO: implement more checks depending on the Category.
        switch ($this->category) {
            case "Laptop":
                if (empty($RAM)){
                    $errors[] = 'Please select a amount of RAM';
                }
                break;
            case "Desktop":
                if (empty($RAM)){
                    $errors[] = 'Please select a amount of RAM';
                }
                break;
            default:
                break;
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
