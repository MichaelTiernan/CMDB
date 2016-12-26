<?php
require_once 'controller.php';
require_once 'Service/AdminService.php';
class AdminController extends Controller{
	private static $sitePart ="Admin";
	private $Level;
	private $adminSerice; 
	
	public function __construct(){
		parent::__construct();
		$this->adminSerice = new AdminService();
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
		$title = 'Delete Account Type';
		$AdminName = $_SESSION["WhoName"];
		$DeleteAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Delete");
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
		$title = 'Update Account Type';
		$errors = array();
		$UpdateAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Update");
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
		$rows = $this->adminSerice->getAll($orderby);
		include 'view/admins.php';
	}
	/**
	 * {@inheritDoc}
	 * @see Controller::save()
	 */
	public function save() {
		$title = 'Add new Account';
		$Level = $_SESSION["Level"];
		$AddAccess= $this->accessService->hasAccess($this->Level, self::$sitePart, "Add");
	
		$AdminName = $_SESSION["WhoName"];
		$Type = '';
		$Description = '';
	
		$errors = array();
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
		if ( !$id ) {
			throw new Exception('Internal error.');
		}
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
			$rows = $this->accountTypeService->search($search);
			include 'view/searched_admins.php';
		}
	}
}