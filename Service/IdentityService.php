<?php
require_once 'ValidationException.php';
require_once 'Service.php';
require_once 'model/IdentityGateway.php';

class IdentityService extends Service{
    private $identityGateway  = NULL;
    
    public function __construct() {
        $this->identityGateway = new IdentityGateway();
    }
    /**
     * This function will validate the parameters
     * @param string $firstname
     * @param string $lastname
     * @param string $company
     * @param string $language
     * @param string $userid
     * @param string $type
     * @param string $email
     * @return Array
     * @throws ValidationException
     */
    private function validateIdentiyParams($firstname, $lastname, $company, $language, $userid, $type, $email, $UUID = 0){
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
        if (empty($language)){
            $errors[] = 'Please select a Language';
        }
        if (empty($userid)){
            $errors[] = 'Please select a UserID';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        if ($UUID > 0){
            if (strcmp($userid, $this->identityGateway->getUserID($UUID)) != 0){
                if ($this->identityGateway->UserIDChecker($userid)){
                    $errors[] = 'UserID already excist in Application please select an other UserID';
                } 
            }
        }  else {
            if ($this->identityGateway->UserIDChecker($userid)){
                $errors[] = 'UserID already excist in Application please select an other UserID';
            }
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    
    private function validateAssignParams($Account,$From,$Until){
        $errors = array();
        if (empty($Account)) {
            $errors[] = 'Please select an Account';
        }
        if (empty($From)){
            $errors[] = 'Please select the From Date';
        }
        if (!empty($From) and !empty($Until)){
            $FromDate = date_create_from_format('d/m/Y',$From);
            $EndDate = date_create_from_format('d/m/Y',$Until);
            if ($EndDate < $FromDate){
                $errors[] = 'The end date is before the from date';
            }
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    
    public function getByID($id){
        try{
            return $this->identityGateway->selectById($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function listAssignedAccount($id){
        return $this->identityGateway->listAssignedAccount($id);
    }

    public function delete($id,$reason,$AdminName){
        try{
            $this->validateDeleteParams($reason); 
            $this->identityGateway->delete($id,$reason,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    
    public function activate($id, $AdminName){
        try{
            $this->identityGateway->activate($id,$AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    public function getAll($order) {
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
    public function createNewIdentity($firstname, $lastname,$company, $language,$userid, $type, $email, $AdminName) {
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email);
            $this->identityGateway->create($firstname,$lastname,$company,$language,$userid,$type,$email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    
    public function updateIdentity ($UUID,$firstname, $lastname,$company, $language,$userid, $type,$email, $AdminName){
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email, $UUID);
            $this->identityGateway->update($UUID, $firstname, $lastname, $userid, $company, $language, $type, $email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }  catch (PDOException $e){
            throw $e;
        }
    }
    public function listAllIdentities(){
        return $this->identityGateway->listAllIdentities();
    }
    public function listAllAccounts(){
        return $this->identityGateway->listAllAccounts();
    }
    public function AssignAccount($UUID,$Account,$From,$Until,$AdminName) {
        try {
            $this->validateAssignParams($Account, $From, $Until);
            $this->identityGateway->AssignAccount($UUID, $Account, $From, $Until, $AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        } catch (PDOException $e){
            throw $e;
        }
    }
    public function search($search){
        return $this->identityGateway->selectBySearch($search);
    }
}
