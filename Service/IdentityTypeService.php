<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/IdentityTypeGateway.php';

class IdentityTypeService extends Service{
    private $identityTypeGateway  = NULL;
    
    public function __construct() {
        $this->identityTypeGateway = new IdentityTypeGateway();
    }
    /**
     * This function will return all active Identity Types
     * @return array
     * @throws PDOException
     */
    public function listAllType(){
        try {
            return $this->identityTypeGateway->getAllTypes();
        } catch (Exception $ex) {
            throw $ex;
        }catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::getAll()
     */
    public function getAll($order) {
        try{
            $rows = $this->identityTypeGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will create a new IdentityType
     * @param string $type
     * @param string $description
     * @param string $AdminName
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($type,$description, $AdminName){
        try {
            $this->validateIdentiyTypeParams($type,$description);
            $this->identityTypeGateway->create($type,$description,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }catch (PDOException $e){
        	throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::delete()
     */
    public function delete($id,$reason,$AdminName){
        try{
            $this->validateDeleteParams($reason); 
            $this->identityTypeGateway->delete($id,$reason,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id) {
        try{
            return $this->identityTypeGateway->selectById($id);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
    /**
     * This function will update a given IdentityType
     * @param int $id
     * @param string $type
     * @param string $description
     * @param string $AdminName
     * @throws ValidationException
     */
    public function uppdate($id,$type,$description,$AdminName){
        try {
            $this->validateIdentiyTypeParams($type,$description);
            $this->identityTypeGateway->update($id,$type,$description,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($UUID,$AdminName) {
        $this->identityTypeGateway->activate($UUID, $AdminName);
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search) {
        return $this->identityTypeGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters
     * @param string $type
     * @param string $description
     * @return array
     * @throws ValidationException
     */
    private function validateIdentiyTypeParams($type,$description){
        $errors = array();
        if (empty($type)) {
            $errors[] = 'Please enter a Type';
        }
        if (empty($description)){
            $errors[] = 'Please enter a Description';
        }
        if ($this->identityTypeGateway->CheckDoubleEntry($type, $description)){
            $errors[] = 'The same Identity Type exist in the Application';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
