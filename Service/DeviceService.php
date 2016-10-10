<?php
require_once 'Service.php';
require_once 'model/DeviceGateway.php';
class DeviceService extends Service{
    private $deviceGateway = NULL;
    private $category = NULL;


    public function __construct() {
        $this->deviceGateway = new DeviceGateway();
    }
	/**
	 * This function will set the Category
	 * @param string $category
	 */
    public function setCategory($category) {
        $this->category = $category;
        $this->deviceGateway->setCategory($category);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::activate()
	 */
    public function activate($id, $AdminName) {
        $this->deviceGateway->activate($id,$AdminName);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
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
	/**
	 * {@inheritDoc}
	 * @see Service::getAll()
	 */
    public function getAll($order) {
        return $this->deviceGateway->selectAll($order);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::getByID()
	 */
    public function getByID($id) {
        return $this->deviceGateway->selectById($id);
    }
    /**
     * This function will create a new Asset
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param string $RAM
     * @param string $IP
     * @param string $Name
     * @param string $MAC
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
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
    /**
     * This function will update a given Asset
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param string $RAM
     * @param string $IP
     * @param string $Name
     * @param string $MAC
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
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
    /**
     * This function will return all AssetTypes for the given category
     * @param string $Category
     * @return array
     */
    public function listAllTypes($Category){
        return $this->deviceGateway->listAllTypes($Category);
    }
    /**
     * This function will return all possibles RAM's
     * @return array
     */
    public function listAllRams(){
        return $this->deviceGateway->listAllRams();
    }
	/**
	 * {@inheritDoc}
	 * @see Service::search()
	 */
    public function search($search) {
        return $this->deviceGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters
     * @param string $AssetTag
     * @param string $SerialNumber
     * @param int $Type
     * @param string $RAM
     * @param string $IP
     * @param string $Name
     * @param string $MAC
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
