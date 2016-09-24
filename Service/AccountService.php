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
     * 
     * @param type $id
     * @param type $AdminName
     */
    public function activate($id, $AdminName) {
        $this->accountGateway->activate($id, $AdminName);
    }
    /**
     * 
     * @param type $id
     * @param type $reason
     * @param type $AdminName
     * @throws ValidationException
     * @throws PDOException
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
     * 
     * @param type $order
     * @return type
     * @throws PDOException
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
     * 
     * @param Integer $id
     * $return Array
     */
    public function getByID($id) {
        return $this->accountGateway->selectById($id);
    }
    /**
     * 
     * @param type $UserID
     * @param type $Type
     * @param type $Application
     * @param type $AdminName
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
     * 
     * @param type $UUID
     * @param type $UserID
     * @param type $Type
     * @param type $Application
     * @param type $AdminName
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
    public function getAllAcounts(){
        return $this->accountGateway->getAllAcounts();
    }
    /**
     * 
     * @param type $id
     * @param type $Identity
     * @param type $start
     * @param type $end
     * @param type $AdminName
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
    
    public function listAllIdentities($id){
        return $this->accountGateway->listAllIdentities($id);
    }

    /**
     * 
     * @param String $userid 
     * @param Integer $type
     * @param Integer $application
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
                    $errors[] = 'This UserID aleray exist in the application';
                }
            }
        }else{
            if ($this->accountGateway->CheckDoubleEntry($userid, $application)){
                $errors[] = 'This UserID aleray exist in the application';
            }
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
    /**
     * 
     * @param type $search
     * @return Array
     */
    public function search($search) {
        return $this->accountGateway->selectBySearch($search);
    }
    
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
}
