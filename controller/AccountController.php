<?php
require_once 'Controller.php';
require_once 'Service/AccountService.php';
require_once 'AccountTypeController.php';
require_once 'ApplicationController.php';
require_once 'IdentityController.php';

class AccountController extends Controller{
    private $accountService = NULL;
    private static $sitePart ="Account";
    private $accountTypeController = NULL;
    private $applicationController = NULL;
    private $identityController = NULL;
    private $Level;

    public function __construct() {
        parent::__construct();
        $this->accountService = new AccountService();
        $this->accountTypeController = new AccountTypeController();
        $this->applicationController = new ApplicationController();
        $this->identityController = new IdentityController();
        $this->Level = $_SESSION["Level"];
    }
    /**
     * This function will return all Accounts
     * @return array
     */
    public function listAllAccounts() {
        return $this->accountService->getAllAcounts();
    }
    /**
     * {@inheritDoc}
     * @see Controller::handleRequest()
     */
    public function handleRequest() {
        $op = isset($_GET['op'])?$_GET['op']:NULL;
        try {
            if ( !$op || $op == 'list' ) {
                $this->listAll();
            } elseif ( $op == 'new' ) {
                $this->save();
            } elseif ( $op == 'delete' ) {
                $this->delete();
            } elseif ( $op == 'show' ) {
                $this->show();
            } elseif ( $op == 'edit' ) {
                $this->edit();
            }elseif ($op == "activate") {
                $this->activate();
            } elseif ($op == "search") {
                $this->search();
            }elseif ($op == "assign"){
                $this->assign();
            } else {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        } 
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::activate()
	 */
    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
        	try{
            	$this->accountService->activate($id,$AdminName);
            	$this->redirect('Account.php');
        	} catch (PDOException $e){
            	$this->showError("Database exception",$e);
            }
        } else {
            $this->showError("Application error", "You do not access to activate a account");
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::delete()
	 */
    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Account';
        $AdminName = $_SESSION["WhoName"];
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->accountService->delete($id,$Reason,$AdminName);
                $this->redirect('Account.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
            	$this->showError("Database exception",$e);
            }
        }
        $rows  = $this->accountService->getByID($id); 
        include 'view/deleteAccount_form.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::edit()
	 */
    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
        $title = 'Update Account';
        $errors = array(); $Level = 
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if ( isset($_POST['form-submitted'])) {
            $UserID = '';
            $Type = '';
            $Application = '';
            $Application  = isset($_POST['Application']) ? $_POST['Application'] :NULL;
            $Type   = isset($_POST['type'])?  $_POST['type'] :NULL;
            $UserID = isset($_POST['UserID'])? $_POST['UserID'] :NULL;
            try{
                $this->accountService->update($id,$UserID,$Type,$Application,$AdminName);
                $this->redirect('Account.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
            	$this->showError("Database exception",$e);
            }
        }else {
            $rows  = $this->accountService->getByID($id);
            foreach ($rows as $row){
                $UserID = $row['UserID'];
                $Application = $row['App_ID'];
                $Type = $row['Type_ID'];
            }
        }
        $types = $this->accountTypeController->listAllTypes();
        $applications = $this->applicationController->listAllApplications();
        include 'view/updateAccount_form.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::listAll()
	 */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->accountService->getAll($orderby);
        include 'view/accounts.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Account';
        $action = "Add";
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, $action);
        
        $AdminName = $_SESSION["WhoName"];
        $UserID = '';
        $Type = '';
        $Application = '';

        $errors = array();
        if ( isset($_POST['form-submitted'])) {
           $Application  = isset($_POST['Application']) ? $_POST['Application'] :NULL;
           $Type   = isset($_POST['type'])?  $_POST['type'] :NULL;
           $UserID = isset($_POST['UserID'])? $_POST['UserID'] :NULL;
           try { 
                $this->accountService->createNew($UserID,$Type,$Application,$AdminName);
                $this->redirect('Account.php');
                return;   
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
            	$this->showError("Database exception",$e);
            }
        }
        $types = $this->accountTypeController->listAllTypes();
        $applications = $this->applicationController->listAllApplications();
        include ($_SERVER["DOCUMENT_ROOT"] .'/CMDB/view/newAccount_form.php');
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::show()
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $AccAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->accountService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('account', $id);
        $accrows = $this->accountService->listAllIdentities($id);
        include 'view/account_overview.php';
    }
    /**
     * This function will be used when assign a account to an Identity
     * @throws ValidationException
     * @throws PDOException
     */
    public function assign(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Update Identity';
        $AdminName = $_SESSION["WhoName"];
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Identity = isset($_POST['identity']) ? $_POST['identity'] :NULL;
            $start = isset($_POST['start']) ? $_POST['start'] :NULL;
            $end = isset($_POST['end']) ? $_POST['end'] :NULL;
            try {
                $this->accountService->AssignIdentity($id, $Identity, $start, $end, $AdminName);
                $this->redirect('Account.php');
                return;
            } catch (ValidationException $exc) {
                $errors = $exc->getErrors();
            } catch (PDOException $e){
                $this->showError("Database exception",$e);
            } 
        }
        $rows = $this->accountService->getByID($id);
        foreach ($rows as $row){
            $UserID = $row['UserID'];
            $Application = $row['App_ID'];
            $Type = $row['Type_ID'];
        }
        $identities = $this->identityController->listAllIdenties();
        include 'view/assignIdentity.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::search()
	 */
    public function search() {
        $search = isset($_POST['search']) ? $_POST['search'] :NULL;
        if (empty($search)){
            $this->listAll();
        }  else {
            $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
            $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
            $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
            $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignIdentity");
            $rows = $this->accountService->search($search);
            include 'view/searched_accounts.php';
        }
    }

}