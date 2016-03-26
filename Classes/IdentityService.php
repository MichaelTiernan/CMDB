<?php
require_once 'IdentityGateway.php';

class IdentityService{
    private $identityGateway  = NULL;

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
}
