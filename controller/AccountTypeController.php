<?php
require_once 'Controller.php';
require_once 'Service/AccountTypeService.php';

class AccountTypeController extends Controller{
    private $accountTypeService = NULL;
    private static $sitePart ="AccountType";
    private $Level;
    
    public function __construct() {
        parent::__construct();
        $this->accountTypeService = new AccountTypeService();
        $this->Level = $_SESSION["Level"];
    }
    
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
            }elseif ($op == "search") {
                $this->search();
            } else {
                $this->showError("Page not found", "Page for operation ".$op." was not found!");
            }
        } catch ( Exception $e ) {
            // some unknown Exception got through here, use application error page to display it
            $this->showError("Application error", $e->getMessage());
        }
    }

    public function activate() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
            $this->accountTypeService->activate($id, $AdminName);
            $this->redirect('AccountType.php');
        }
    }

    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Account Type';
        $AdminName = $_SESSION["WhoName"];
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->accountTypeService->delete($id,$Reason,$AdminName);
                $this->redirect('AccountType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->accountTypeService->getByID($id);
        foreach($rows as $row){
            $Type = $row["Type"];
            $Description = $row["Description"];
        }
        include 'view/deleteAccountType_form.php';
    }

    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
        $title = 'Update Account Type';
        $errors = array();
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->accountTypeService->uppdateIdentityType($id, $Type, $Description, $AdminName);
                $this->redirect('AccountType.php');
                return;   
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }else {
            $rows = $this->accountTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        include 'view/updateAccountType_form.php';
    }
    
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->accountTypeService->getAll($orderby);
        include 'view/accounttypes.php';
    }

    public function save() {
        $title = 'Add new Account';
        $Level = $_SESSION["Level"];
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                
                $this->accountTypeService->create($Type, $Description, $AdminName);
                $this->redirect('accounttype.php');
                return;
                
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }
        include 'view/newAccountType_form.php';
    }

    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->accountTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('accounttype', $id);
        
        include 'view/accounttype_overview.php';
    }
    
    public function listAllTypes(){
        return $this->accountTypeService->listAllTypes();
    }

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
            $rows = $this->accountTypeService->search($search);
            include 'view/searched_accounttypes.php';
        }
    }

}
