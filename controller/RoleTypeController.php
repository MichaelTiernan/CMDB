<?php
require_once 'Controller.php';
require_once 'Service/RoleTypeService.php';
class RoleTypeController extends Controller{
    private static $sitePart ="RoleType";
    private $Level;
    private $roleTypeService = NULL;
    
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->roleTypeService = new RoleTypeService();
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
        $AdminName = $_SESSION["WhoName"];
        $this->roleTypeService->activate($id, $AdminName);
        $this->redirect('RoleType.php');
    }

    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Role Type';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->roleTypeService->delete($id, $Reason, $AdminName);
                //$_POST = array();
                $this->redirect('RoleType.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->roleTypeService->getByID($id);
        foreach($rows as $row){
            $Type = $row["Type"];
            $Description = $row["Description"];
        }
        include 'view/deleteRoleType_form.php';
    }

    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Update Role Type';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type = '';
            $Description = '';
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try{
                $this->roleTypeService->update($id,$Type,$Description,$AdminName);
                $this->redirect('RoleType.php');
                return;
                
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }  else {
            $rows = $this->roleTypeService->getByID($id);
            foreach($rows as $row){
                $Type = $row["Type"];
                $Description = $row["Description"];
            }
        }
        include 'view/updateRoleType_form.php';
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
        $rows = $this->roleTypeService->getAll($orderby);
        include 'view/roleTypes.php';
    }

    public function save() {
        $title = 'Add new Role Type';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Type = '';
        $Description = '';
        
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Description = isset($_POST['Description'])?  $_POST['Description'] :NULL;
            try {
                $this->roleTypeService->create($Type, $Description, $AdminName);
                $this->redirect('RoleType.php');
                return;           
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        include 'view/newRoleType_form.php';
    }

    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->roleTypeService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('roletype', $id);
        include 'view/roletype_overview.php';
    }
    
    public function listAllType(){
        return $this->roleTypeService->listAllType();
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
            $rows = $this->roleTypeService->search($search);
            include 'view/searched_roles.php';
        }
    }

}
