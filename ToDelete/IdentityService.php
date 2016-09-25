<?php
require_once 'IdentityGateway.php';
require_once 'ValidationException.php';

class IdentityService{
    private $identityGateway  = NULL;
    
    private function validateIdentiyParams($firstname, $lastname, $userid, $type){
        $errors = array();
        if (empty($firstname)) {
            $errors[] = 'Please enter First Name';
        }
        if (empty($lastname)) {
            $errors[] = 'Please enter Last Name';
        }
        if (empty($type)) {
            $errors[] = 'Please select a Type';
        }
        if ( empty($errors) ) {
            return;
        }
        throw new ValidationException($errors);
    }

        public function __construct() {
        $this->identityGateway = new IdentityGateway();
    }
    
    public function getAllContacts($order) {
        try{
            $rows = $this->identityGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            print $e;
        }
    }
    
    /**
     * This function will try to insert a new row into the db
     * @param string $firstname
     * @param string $lastname
     * @param string $userid
     * @param int $type
     */
    public function createNewIdentity($firstname, $lastname, $userid, $type) {
        try {
            $AdminName = "Root";
            $this->validateIdentiyParams($firstname,$lastname,$userid,$type);
            $this->identityGateway->create($firstname, $lastname, $userid, $type, $AdminName, $pdo);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
}
