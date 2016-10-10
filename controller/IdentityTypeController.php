<?php
require_once 'Service/IdentityTypeService.php';
require_once 'Controller.php';

class IdentityTypeController extends Controller{
    private $identityTypeService = NULL;
    private static $sitePart ="IdentityType";
    private $Level;
    
    public function __construct() {
        $this->identityTypeService = new IdentityTypeService();
        $this->Level = $_SESSION["Level"];
        parent::__construct();
    }
    /**
     * This function will return all IdentityTypes
     * @return array
     */
    public function listAllType(){
        return $this->identityTypeService->listAllType();
    }
    /**
     * {@inheritDoc}
     * @see Controller::handleRequest()
     */
    public function handleRequest(){
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
	/**
	 * {@inheritDoc}
	 * @see Controller::listAll()
	 */
    public function listAll(){
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
        $rows = $this->identityTypeService->getAll($orderby);
        include 'view/identityTypes.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::save()
     * @throws PDOException
     */
    public function save(){
        $title = 'Add new Identity Type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                $this->identityTypeService->create($Type, $Description, $AdminName);
                $this->redirect('IdentityType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        include 'view/newIdentityType_form.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::delete()
     * @throws PDOException
     */
    public function delete(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Identity Type';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->identityTypeService->delete($id,$Reason,$AdminName);
                //$_POST = array();
                $this->redirect('IdentityType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->identityTypeService->getByID($id);
        foreach($rows as $row){
            $Type = $row["Type"];
            $Description = $row["Description"];
        }
        include 'view/deleteIdentityType_form.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::activate()
     */
    public function activate(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $AdminName = $_SESSION["WhoName"];
        $this->identityTypeService->activate($id,$AdminName);
        $this->redirect('IdentityType.php');
    }
    /**
     * {@inheritDoc}
     * @see Controller::show()
     */
    public function show(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->identityTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('identitytype', $id);
        include 'view/identitytype_overview.php';
    }
    /**
     * {@inheritDoc}
     * @see Controller::edit()
     * @throws PDOException
     */
    public function edit(){
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Update Identity Type';
        $Level = $_SESSION["Level"];
        $sitePart = "IdentityType";
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->identityTypeService->uppdate($id, $Type, $Description, $AdminName);
                $this->redirect('IdentityType.php');
                return;
                
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }  else {
            $rows = $this->identityTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        include 'view/updateIdentityType_form.php';
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
            $rows = $this->identityTypeService->search($search);
            include 'view/searched_identityTypes.php';
        }
    }
}