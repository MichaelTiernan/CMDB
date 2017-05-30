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
     * {@inheritDoc}
     * @see Service::getByID()
     */    
    public function getByID($id){
        try{
            return $this->identityGateway->selectById($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    /**
     * This function will list all Assigned Accounts to an Identity
     * @param int $id The Unique id of the Identity
     * @return Array
     */
    public function listAssignedAccount($id){
        return $this->identityGateway->listAssignedAccount($id);
    }
    /**
     * This function will list all Assigned Devices to an Identity
     * @param int $id The Unique id of the Identity
     * @return Array
     */
    public function listAssignedDevices($id){
        return $this->identityGateway->listAssignedDevices($id);
    }
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
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
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName){
        try{
            $this->identityGateway->activate($id,$AdminName);
        } catch (PDOException $ex) {
            throw $ex;
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Service::getAll()
	 */
    public function getAll($order) {
        try{
            $rows = $this->identityGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            print $e;
        }
    }    
    /**
     * This function will create a new Identity
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param int $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
     * @param string $AdminName The name of the person who did the creation
     * @throws ValidationException
     */
    public function create($firstname, $lastname,$company, $language,$userid, $type, $email, $AdminName) {
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email);
            $this->identityGateway->create($firstname,$lastname,$company,$language,$userid,$type,$email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }
    }
    /**
     * This function will update the given Identiy
     * @param int $UUID The unique ID of the Identity
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param int $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
     * @param string $AdminName The name of the person who did the update
     * @throws ValidationException
     * @throws PDOException
     */
    public function update ($UUID,$firstname, $lastname,$company, $language,$userid, $type,$email, $AdminName){
        try {
            $this->validateIdentiyParams($firstname,$lastname,$company,$language,$userid,$type, $email, $UUID);
            $this->identityGateway->update($UUID, $firstname, $lastname, $userid, $company, $language, $type, $email,$AdminName);
        } catch (ValidationException $ex) {
            throw $ex;
        }  catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update all Identities
     * @return array
     */
    public function listAllIdentities(){
        return $this->identityGateway->listAllIdentities();
    }
    /**
     * This function will list all Accounts
     */
    public function listAllAccounts(){
        return $this->identityGateway->listAllAccounts();
    }
    /**
     * This function will assign an Identity to an Account
     * @param int $UUID THe unique id of the Identity
     * @param int $Account The unique ID of the account
     * @param DateTime $From The From date
     * @param DateTime $Until The Until Date
     * @param string $AdminName The name of the person who did the Assign
     * @throws ValidationException
     * @throws PDOException
     */
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
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search){
        return $this->identityGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters
     * @param string $firstname The fist name of the Identity
     * @param string $lastname The Last Name of the Identity
     * @param string $company The name of the company of the Identity
     * @param string $language The language of the Identity
     * @param string $userid The UserID of the Identity
     * @param string $type The Type of the Identity
     * @param string $email The e-mail address of the Identity
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
    /**
     * This function will validate the parameters during assign
     * @param int $Account The ID of the Account
     * @param DateTime $From The From Date
     * @param DateTime $Until The Until Date
     * @throws ValidationException
     */
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
}
