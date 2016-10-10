<?php
require_once 'Controller.php';
require_once 'Service/TokenService.php';
class TokenController extends Controller{
    private static $sitePart ="Token";
    private $Level = NULL;
    private $tokenService = NULL;


    public function __construct() {
        parent::__construct();
        $this->Level = $_SESSION["Level"];
        $this->tokenService = new TokenService();
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
        $this->tokenService->activate($id,$AdminName);
        $this->redirect('Token.php');
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
        $title = 'Delete Token';
        $AdminName = $_SESSION["WhoName"];
        
        $Reason = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $Reason = isset($_POST['reason']) ? $_POST['reason'] :NULL;
            try {
                $this->tokenService->delete($id, $Reason, $AdminName);
                //$_POST = array();
                $this->redirect('Token.php');
                return;
            } catch (ValidationException $e){
                $errors = $e->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }
        $rows = $this->tokenService->getByID($id);
        include 'view/deleteToken_form.php'; 
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
        $title = 'Update Token';
        $AdminName = $_SESSION["WhoName"];
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            try{
                $this->tokenService->update($AssetTag,$SerialNumber,$Type, $AdminName);
                $this->redirect('Token.php');
                return;   
            } catch (ValidationException $ex) {
                $errors = $ex->getErrors();
            } catch (PDOException $e){
                throw $e;
            }
        }  else {
            $rows = $this->tokenService->getByID($id);
            foreach ($rows as $row):
                $AssetTag = $row["AssetTag"];
                $SerialNumber = $row["SerialNumber"];
                $Type = $row["Type_ID"];
            endforeach;
        }
        echo 'Type: '.$Type;
        $typerows = $this->tokenService->listAllTypes();
        include 'view/updateToken_form.php';
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
        if (isset($_GET['orderby'])){
            $orderby = $_GET['orderby'];
        }else{
            $orderby = "";
        }
        $rows = $this->tokenService->getAll($orderby);
        include 'view/tokens.php';
    }
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
    public function save() {
        $title = 'Add new token';
        $AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
        $AdminName = $_SESSION["WhoName"];
        $AssetTag = '';
        $SerialNumber = '';
        $Type = '';
        $errors = array();
        if ( isset($_POST['form-submitted'])) {
            //print_r($_POST);
            $AssetTag = isset($_POST['AssetTag']) ? $_POST['AssetTag'] :NULL;
            $SerialNumber = isset($_POST['SerialNumber']) ? $_POST['SerialNumber'] :NULL;
            $Type = isset($_POST['Type']) ? $_POST['Type'] :NULL;
            
            try {
                $this->tokenService->create($AssetTag, $SerialNumber, $Type, $AdminName);
                $this->redirect('Token.php');
                return;
            } catch (ValidationException $ex) {
               $errors = $ex->getErrors();
            } catch (PDOException $e){
                print $e->getMessage();
            }
        }
        $typerows = $this->tokenService->listAllTypes();
        include 'view/newToken_form.php';
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
            $rows = $this->tokenService->search($search);
            include 'view/searched_tokens.php';
        }
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
        $IdenViewAccess = $this->accessService->hasAccess($this->Level, self::$sitePart, "IdentityOverview");
        $rows = $this->tokenService->getByID($id);
        $idenrows = $this->tokenService->listOfAssignedIdentities($id);
        $logrows = $this->loggerController->listAllLogs('token', $id);
        include 'view/token_overview.php';
    }
}
