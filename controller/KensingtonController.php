<?php
require_once 'Controller.php';
require_once 'Service/KensingtonService.php';
class KensingtonController extends Controller{
    private $kensingtoneService = NULL;
    private $Level;
    private static $sitePart = "Kensington";
    /**
     * Constroctor
     */
    public function __construct() {        
        $this->kensingtoneService = new KensingtonService();
        $this->Level = $_SESSION["Level"];
        parent::__construct();
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
        $this->kensingtoneService->activate($id,$AdminName);
        $this->redirect('Kensington.php');
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
        $title = 'Delete Kensington';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->kensingtoneService->delete($id,$Reason,$AdminName);
                $this->redirect('Kensington.php');
                return;
            }  catch (Exception $e){
                $errors = $e->getErrors();
            }
        }
        $rows = $this->kensingtoneService->getByID($id);
        include 'view/deleteKensington_form.php';
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
        $title = 'Update Kensington';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Serial  = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $NrKeys  = isset($_POST['Keys']) ? $_POST['Keys'] :NULL;
            $hasLock  = isset($_POST['Lock']) ? $_POST['Lock'] :NULL;
            try {
                $this->kensingtoneService->edit($id, $Type, $Serial, $NrKeys, $hasLock, $AdminName);
                $this->redirect("kensington.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }else{
            $rows = $this->kensingtoneService->getByID($id);
            foreach ($rows as $row):
                $Type = $row["Type_ID"];
                $Serial = $row["Serial"];
                $NrKeys = $row["AmountKeys"];
                $hasLock = $row["hasLock"];
            endforeach;
        }
        $types = $this->kensingtoneService->listAllTypes();
        include 'view/updateKensington_form.php';
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
            } elseif ($op == "activate") {
                $this->activate();
            } elseif ($op == "assign") {
                $this->assign();
            } elseif ($op == "search") {
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
    public function listAll() {
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $InfoAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
        $ActiveAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Activate");
        $UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
        $AssignAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "AssignAccount");
        //$orderby = isset($_GET['orderby'])?$_GET['orderby']:NULL;
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->kensingtoneService->getAll($orderby);
        include 'view/kensingtons.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new Kensington';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        $Type ='';
        $Serial = '';
        $NrKeys = '';
        $hasLock = '';
        if ( isset($_POST['form-submitted'])) {
            $Type  = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            $Serial  = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $NrKeys  = isset($_POST['Keys']) ? $_POST['Keys'] :NULL;
            $hasLock  = isset($_POST['Lock']) ? $_POST['Lock'] :NULL;  
            try {
                $this->kensingtoneService->add($Type,$Serial,$NrKeys,$hasLock,$AdminName);
                $this->redirect("kensington.php");
                return;
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e;
            }
        }
        $types = $this->kensingtoneService->listAllTypes();
        include 'view/newKensington_form.php';
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
            $rows = $this->kensingtoneService->search($search);
            include 'view/searched_kensingtons.php';
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::show()
	 */
    public function show() {
        $id = isset($_GET['id'])?$_GET['id']:NULL;
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $ViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "Read");
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "DeviceOverview");
        if ( !$id ) {
            throw new Exception('Internal error.');
        }
        $rows = $this->kensingtoneService->getByID($id);
        $logrows = $this->loggerController->listAllLogs('kensington', $id);
        $idenrows = $this->kensingtoneService->listAssets($id);
        include 'view/kensington_overview.php';
    }

}
