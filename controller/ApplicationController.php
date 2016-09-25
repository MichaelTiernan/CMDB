<?php
require_once 'Controller.php';
require_once 'Service/ApplicationService.php';
class ApplicationController extends Controller{
    private $applicationService = NULL;
    private $Level;
    private static $sitePart = "Application";
    
    public function __construct() {
        parent::__construct();
        $this->applicationService = new ApplicationService();
        $this->Level = $_SESSION["Level"];
    }
    /**
     * This function will return all applications
     * @return array
     */
    public function listAllApplications(){
        return $this->applicationService->listAllApplications();
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
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $AdminName = $_SESSION["WhoName"];
        if ($ActiveAccess){
        	$this->applicationService->activate($id, $AdminName);
        	$this->redirect('Application.php');
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
        $title = 'Delete Application';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->applicationService->delete($id,$Reason,$AdminName);
                $this->redirect('Application.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->applicationService->getByID($id);
        foreach ($rows as $row){
            $Name = $row["Name"];
        }
        include 'view/deleteApplication_form.php';
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
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $title = 'Update Application';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
        	$Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
        	try{
        		$this->applicationService->edit($id,$Name,$AdminName);
        		$this->redirect('Application.php');
        		return;
        	}catch (ValidationException $e){
        		$errors = $e->getErrors();
        	}catch (PDOException $ex){
        		print $ex;
        	}
        }else {
        	$rows = $this->applicationService->getByID($id);
        	foreach ($rows as $row):
        		$Name = $row["Name"];
        	endforeach;
        }
        include 'view/updateApplication_form.php';
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
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->applicationService->getAll($orderby);
        include 'view/applications.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Application';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $Name = "";
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Name = isset($_POST['Name']) ? $_POST['Name'] :NULL;
            try{
                $this->applicationService->createNewApplication($Name,$AdminName);
                $this->redirect('Application.php');
                return;
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
            } catch (PDOException $ex){
                print $ex;
            }
        }
        include 'view/newApplication_form.php';
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
        $AccAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "AccountOverview");
        $logrows = $this->loggerController->listAllLogs('identity', $id);
        $rows = $this->applicationService->getByID($id);
        $accrows = $this->applicationService->listAllAccounts($id);
        include 'view/application_overview.php';
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
            $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
            $rows = $this->applicationService->search($search);
            include 'view/searched_applications.php';
        }
    }

}
