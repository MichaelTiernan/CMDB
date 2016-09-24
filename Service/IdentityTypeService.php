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
     * 
     * @return Array
     * @throws Exception
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
     * 
     * @param String $order
     * @return Array
     * @throws PDOException
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
     * 
     * @param type $type
     * @param type $description
     * @param type $AdminName
     * @throws ValidationException
     */
    public function create($type,$description, $AdminName){
        try {
            $this->validateIdentiyTypeParams($type,$description);
            $this->identityTypeGateway->create($type,$description,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    /**
     * 
     * @param type $id
     * @param type $reason
     * @param type $AdminName
     * @throws ValidationException
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
     * 
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function getByID($id) {
        try{
            return $this->identityTypeGateway->selectById($id);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
    public function uppdate($id,$type,$description,$AdminName){
        try {
            $this->validateIdentiyTypeParams($type,$description);
            $this->identityTypeGateway->update($id,$type,$description,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    public function activate($UUID,$AdminName) {
        $this->identityTypeGateway->activate($UUID, $AdminName);
    }
    /**
     * 
     * @param type $search
     * @return type
     */
    public function search($search) {
        return $this->identityTypeGateway->selectBySearch($search);
    }
    /**
     * 
     * @param type $type
     * @param type $description
     * @return type
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
