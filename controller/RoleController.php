<?php
require_once 'Controller.php';
require_once 'RoleTypeController.php';
require_once 'Service/RoleService.php';
class RoleController extends Controller{
    private static $sitePart ="RoleType";
    private $Level = NULL;
    private $roleService = NULL;
    private $roleTypeController = NULL;

    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->roleService = new RoleService();
        $this->roleTypeController = new RoleTypeController();
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
            }elseif ($op == "assign"){
                $this->assign();
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
        try{
            $this->roleService->activate($id, $AdminName);
            $this->redirect('Role.php');
        } catch (PDOException $e){
            print $e;
        }
    }

    public function delete() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Delete Role';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->roleService->delete($id,$Reason,$AdminName);
                $this->redirect('Role.php');
                return;
            }  catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        } 
        $rows = $this->roleService->getByID($id);   
        include 'view/deleteRole_form.php';
    }

    public function edit() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $title = 'Update Role';
        $AddAccess =$this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST["Name"]) ? $_POST["Name"] : NULL;
            $Description = isset($_POST["Description"]) ? $_POST["Description"] : NULL;
            $Type = isset($_POST["type"]) ? $_POST["type"] : NULL;
            try{
                $this->roleService->update($id,$Name, $Description, $Type, $AdminName);
                $this->redirect('Role.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }else{
            $rows = $this->roleService->getByID($id); 
            foreach ($rows as $row){
                $Name = $row["Name"];
                $Description = $row["Description"];
                $Type = $row["Type_ID"];
            }
        }
        $types = $this->roleTypeController->listAllType();
        include 'view/updateRole_form.php';
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
        $rows = $this->roleService->getAll($orderby);
        include 'view/roles.php';
    }

    public function save() {
        $title = 'Add new Role';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $Name = "";
        $Description = "";
        $Type = "";
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST["Name"]) ? $_POST["Name"] : NULL;
            $Description = isset($_POST["Description"]) ? $_POST["Description"] : NULL;
            $Type = isset($_POST["type"]) ? $_POST["type"] : NULL;
            try{
                $this->roleService->create($Name, $Description, $Type, $AdminName);
                $this->redirect('Role.php');
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }
        $types = $this->roleTypeController->listAllType();
        include 'view/newRole_form.php';
    }

    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->roleService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('role', $id);
        include 'view/role_overview.php';
    }
    
    public function assign(){
        
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
            $rows = $this->roleService->search($search);
            include 'view/searched_roles.php';
        }
    }

}
