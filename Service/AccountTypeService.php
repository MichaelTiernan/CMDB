<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/AccountTypeGateway.php';

class AccountTypeService extends Service{
    private $accountTypeGateway = NULL;
    public function __construct() {
        $this->accountTypeGateway = new AccountTypeGateway();
    }
	/**
	 * {@inheritDoc}
	 * @see Service::activate()
	 */
    public function activate($id, $AdminName) {
        $this->accountTypeGateway->activate($id, $AdminName);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
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
	/**
	 * {@inheritDoc}
	 * @see Service::getAll()
	 */
    public function getAll($order) {
        return $this->accountTypeGateway->selectAll($order);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::getByID()
	 */
    public function getByID($id) {
        return $this->accountTypeGateway->selectById($id);
    }
    /**
     * This function will return all Types
     * @return array
     */
    public function listAllTypes() {
        return $this->accountTypeGateway->getAllTypes();
    }
    /**
     * This function will create a new Account Type
     * @param string $type The type of the accounttype
     * @param string $description the description for this type
     * @param string $AdminName The name of the person hwo did the creation
     * @throws ValidationException
     * @throws PDOException
     */
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
    /**
     * This function will update a given account type
     * @param int $UUID The Unique ID of the AccountType
     * @param string $type The type of the accounttype
     * @param string $description The description for this type
     * @param string $AdminName The name of the person hwo did the creation
     * @throws ValidationException
     * @throws PDOException
     */
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
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search) {
        return $this->accountTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the paramaters
     * @param string $type The type of the accounttype
     * @param string $description The description for this type
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
