<?php
require_once 'model/AccessGateway.php';
require_once 'Service.php';
class AccessService extends Service {
    private $accessGateway = NULL;
    
    public function __construct() {
        $this->accessGateway = new AccessGateway();
    }
    /**
     * This function will check if the level has acces to the tequested source
     * @param int $level
     * @param string $sitePart
     * @param string $action
     * @return boolean
     */
    public function hasAccess($level,$sitePart,$action) {
        return $this->accessGateway->hasAccess($level, $sitePart, $action);
    }
    /**
     * This function will retrun the third level of the menu
     * @param int $level
     * @param int $menuid
     */
    public function getThirdLevel($level,$menuid){
        return $this->accessGateway->getMenu($level,$menuid);
    }
    /**
     * This function will return all first levels
     * @return array
     */
    public function getFirstLevel(){
        return $this->accessGateway->getFrirst();
    }
    /**
     * This  function will return the second level
     * @param int $menuid
     */
    public function getSecondLevel($menuid){
        return $this->accessGateway->getSecond($menuid);
    }
    /**
     * {@inheritDoc}
     * @see Service::getAll()
     */
    public function getAll($order){
        return $this->accessGateway->selectAll($order);
    }
    /**
     * this function will return all Second levels
     * @return array
     */
    public function listSecondLevel(){
        return $this->accessGateway->listSecondLevel();
    }
    /**
     * This function will return all possible Permissions
     * @return array
     */
    public function listAllPermissions(){
        return $this->accessGateway->listAllPermissions();
    }
    /**
     * This function will return all Levels
     * @return array
     */
    public function listAllLevels(){
        return $this->accessGateway->listAllLevels();
    }
    /**
     * This function will create a new permission
     * @param int $Level The level of Admin
     * @param int $menu The id of the menu the Admin will access
     * @param int $permission The ID of the permission
     * @param string $AdminName The Name of the Admin who is adding the permission
     * @throws ValidationException
     * @throws PDOException
     */
    public function create($Level,$menu,$permission,$AdminName){
        try {
            $this->validatePermission($Level,$menu,$permission);
            $this->accessGateway->create($Level, $menu, $permission, $AdminName);
        } catch (ValidationException $exc) {
            throw $exc;
        } catch (PDOException $e){
            throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::getByID()
     */
    public function getByID($id){
        return $this->accessGateway->selectById($id);
    }
    /**
     * {@inheritDoc}
     * @see Service::activate()
     */
    public function activate($id, $AdminName) {
        try {
        	$this->accessGateway->activate($id, $AdminName);
        } catch (PDOException $e) {
        	throw $e;
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
    public function delete($id, $reason, $AdminName) {
        try {
        	$this->accessGateway->delete($id, $reason, $AdminName);
        }catch (PDOException $e){
        	throw $e;
        }
    }
    /**
     * {@inheritDoc}
     * @see Service::search()
     */
    public function search($search) {
        return $this->accessGateway->selectBySearch($search);
    }
    /**
     * This function will validate the Parameters
     * @param int $Level
     * @param int $menu
     * @param int $permission
     * @throws ValidationException
     */
    private function validatePermission($Level,$menu,$permission){
        $errors = array();
        if (empty($Level)) {
            $errors[] = 'Please select an Level';
        }
        if (empty($menu)) {
            $errors[] = 'Please select an Menu';
        }
        if (empty($permission)) {
            $errors[] = 'Please select an Permission';
        }
        if ($this->accessGateway->dubbelChecker($Level, $menu, $permission)){
            $errors[] = 'Permission already exist in database';
        }
        if ( empty($errors) ) {
            return;
        }
        
        throw new ValidationException($errors);
    }
}
