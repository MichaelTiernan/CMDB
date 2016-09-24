<?php
require_once 'model/AccessGateway.php';
require_once 'Service.php';
class AccessService extends Service {
    private $accessGateway = NULL;
    
    public function __construct() {
        $this->accessGateway = new AccessGateway();
    }
    
    public function hasAccess($level,$sitePart,$action) {
        return $this->accessGateway->hasAccess($level, $sitePart, $action);
    }
    
    public function getThirdLevel($level,$menuid){
        return $this->accessGateway->getMenu($level,$menuid);
    }
    
    public function getFirstLevel(){
        return $this->accessGateway->getFrirst();
    }
    public function getSecondLevel($menuid){
        return $this->accessGateway->getSecond($menuid);
    }
    public function getAll($order){
        return $this->accessGateway->selectAll($order);
    }
    public function listSecondLevel(){
        return $this->accessGateway->listSecondLevel();
    }
    public function listAllPermissions(){
        return $this->accessGateway->listAllPermissions();
    }
    public function listAllLevels(){
        return $this->accessGateway->listAllLevels();
    }
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
    public function getByID($id){
        return $this->accessGateway->selectById($id);
    }
    
    public function activate($id, $AdminName) {
        
    }

    public function delete($id, $reason, $AdminName) {
        
    }
    
    public function search($search) {
        return $this->accessGateway->selectBySearch($search);
    }
    
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
