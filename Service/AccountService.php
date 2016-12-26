<?php
require_once 'Service.php';
require_once 'ValidationException.php';
require_once 'model/AccountGateway.php';

class AccountService extends Service {
    private $accountGateway;
    
    public function __construct() {
        $this->accountGateway = new AccountGateway();
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName) {
        $this->accountGateway->activate($id, $AdminName);
    }
    /**
     * {@inheritDoc}
     * @see Service::delete()
     */
    public function delete($id, $reason, $AdminName) {
        try{
            $this->validateDeleteParams($reason);
            $this->accountGateway->delete($id, $reason, $AdminName);
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
        try{
            $rows = $this->accountGateway->selectAll($order);
            return $rows;
        }  catch (PDOException $e){
            print $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id) {
        return $this->accountGateway->selectById($id);
    }
    /**
     * This function will create a new Account
     * @param string $UserID The UserID of the Account
     * @param int $Type The ID of the AccountType
     * @param int $Application The ID of the Application
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function createNew($UserID,$Type,$Application,$AdminName){
        try {
            $this->validateAccountParams($UserID, $Type, $Application);
            $this->accountGateway->create($UserID, $Type, $Application, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will update a given Account
     * @param int $UUID The unique ID of the Account
     * @param string $UserID The UserID of the Account
     * @param int $Type The ID of the AccountType
     * @param int $Application The ID of the Application
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function update($UUID,$UserID,$Type,$Application,$AdminName) {
        try {
            $this->validateAccountParams($UserID, $Type, $Application, $UUID);
            $this->accountGateway->update($UUID,$UserID, $Type, $Application, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will return all Accounts
     * @return array
     */
    public function getAllAcounts(){
        return $this->accountGateway->getAllAcounts();
    }
    /**
     * This function will assign an Account to an Identity
     * @param int $id The unique ID of the Account
     * @param int $Identity The unique ID of the Identity
     * @param DateTime $start The startDate
     * @param DateTime $end The EndDate
     * @param string $AdminName The name of the Administrator
     * @throws ValidationException
     * @throws PDOException
     */
    public function AssignIdentity($id, $Identity, $start, $end, $AdminName){
        try {
            $this->validateAssignParams($Identity, $start,$end);
            $this->accountGateway->AssignIdentity($id, $Identity, $start, $end, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * This function will list all Identities assigned to an Account
     * @param int $id The ID of the Account
     */
    public function listAllIdentities($id){
        return $this->accountGateway->listAllIdentities($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search) {
        return $this->accountGateway->selectBySearch($search);
    }
    /**
     * This function will validate the parameters during assign
     * @param int $Identity The unique ID of the Identity
     * @param DateTime $From The From Date
     * @param DateTime $Until The Until date
     * @throws ValidationException
     */
    private function validateAssignParams($Identity,$From,$Until){
        $errors = array();
        if (empty($Identity)) {
            $errors[] = 'Please select an Identity';
        }
        if (empty($From)){
            $errors[] = 'Please select the From Date';
        }
        if (!empty($From) and !empty($Until)){
            $FromDate =date_create_from_format('d/m/Y',$From);
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
    /**
     * This function will validate the parameters and throw an exception
     * @param string $userid The UserID of the Account
     * @param int $type The ID of the AccountType
     * @param int $application The ID of the Application
     * @throws ValidationException
     */
    private function validateAccountParams($userid, $type, $application, $UUID = 0){
    	$errors = array();
    	if (empty($type)) {
    		$errors[] = 'Please select a Type';
    	}
    	if (empty($userid)){
    		$errors[] = 'Please select a UserID';
    	}
    	if (empty($application)){
    		$errors[] = 'Please select a Application';
    	}
    	if ($UUID > 0){
    		if (strcmp($userid, $this->accountGateway->getUserID($UUID)) != 0){
    			if ($this->accountGateway->CheckDoubleEntry($userid, $application)){
    				$errors[] = 'This UserID already exist in the application';
    			}
    		}
    	}else{
    		if ($this->accountGateway->CheckDoubleEntry($userid, $application)){
    			$errors[] = 'This UserID already exist in the application';
    		}
    	}
    	if ( empty($errors) ) {
    		return;
    	}
    
    	throw new ValidationException($errors);
    }
}
