<?php
require_once 'Controller.php';
require_once 'Service/AccessService.php';

class PermissionController extends Controller{
    private static $sitePart ="Permissions";
    private $Level = NULL;
                
    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
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
    /**
     * {@inheritDoc}
     * @see Controller::activate()
     */
    public function activate() {
    	$id = isset($_GET['id'])?$_GET['id']:NULL;
    	if ( !$id ) {
    		throw new Exception('Internal error.');
    	}
    	$AdminName = $_SESSION["WhoName"];
    	$ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
    	if ($ActiveAccess){
    		$this->accessService->activate($id, $AdminName);
    	} else {
            $this->showError("Application error", "You do not access to activate a application");
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
    	$title = 'Delete Permission';
    	$errors = array();
    	$AdminName = $_SESSION["WhoName"];
    	if (isset($_POST['form-submitted'])){
    		try {
    			$reason = "Just because";
    			$this->accessService->delete($id, $reason, $AdminName);
    			 $this->redirect("Permission.php");
                return;
    		}catch (PDOException $ex){
    			$this->showError("Database exception",$e);
    		}
    	}
    	$rows = $this->accessService->getByID($id);
    	include 'view/deletePermission_Form.php';
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
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::listAll()
	 */
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignLevel");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->accessService->getAll($orderby);
        include 'view/permissions.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $AdminName = $_SESSION["WhoName"];
        $title = "Create Permission";
        $errors = array();
        
        if ( isset($_POST['form-submitted'])) {
            $Level = isset($_POST['Level']) ? $_POST['Level'] :NULL;
            $menu = isset($_POST['menu']) ? $_POST['menu'] :NULL;
            $permission = isset($_POST['permission']) ? $_POST['permission'] :NULL;
            try {
                $this->accessService->create($Level,$menu,$permission,$AdminName);
                $this->redirect("Permission.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                $this->showError("Database exception",$e);
            }
        }
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $Menus = $this->accessService->listSecondLevel();
        $Perms = $this->accessService->listAllPermissions();
        $Levels = $this->accessService->listAllLevels();
        include 'view/newPermission_Form.php';
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
        $permAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "PermissionOverview");
        $menuAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "MenuOverview");
       
        $rows = $this->accessService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('role_perm', $id);
        include 'view/permission_overview.php';
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
            $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
            $AssignAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignLevel");
            $rows = $this->accessService->search($search);
            include 'view/searched_permissions.php';
        }
    }
}
