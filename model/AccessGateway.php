<?php
require_once 'Logger.php';
class AccessGateway extends Logger{
    /**
     * This variable will keep the table for the logging
     * @var string
     */
	private static $table = 'role_perm';
    /**
     * Check if the level has access to the requested source
     * @param int $level The level of the Administrator 
     * @param string $sitePart the Source 
     * @param string $action the action that will be performed
     * @return boolean
     */
    public function hasAccess($level,$sitePart,$action) {
        $pdo = Logger::connect();
        $SQL = "Select rp.role_perm_id, Level, M.label Menu, Permission "
                . "from role_perm rp "
                . "join Menu m on rp.menu_id = m.Menu_id "
                . "join permissions p on rp.perm_id = p.perm_id "
                . "where rp.level =:level and m.label =:part and p.permission = :action";
        $q = $pdo->prepare($SQL);
        $q->bindParam(':level',$level);
        $q->bindParam(':part',$sitePart);
        $q->bindParam(':action',$action);
        $q->execute();
        $row = $q->fetchAll(PDO::FETCH_ASSOC);
//        print $SQL."<br>";
//        print "Level: ".$level." Site: ".$sitePart." Action: ".$action."<br>";
//        print_r($row);
//        print "<br>";
        if ($q->rowCount()>0){
            return TRUE;
        }  else {
            return FALSE;
//            return TRUE;
        }
        Logger::disconnect();
    }
    /**
     * Return the first Level of the menu
     * @return array
     */
    public function getFrirst(){
        $pdo = Logger::connect();
        $SQL = "SELECT * from Menu where parent_id = 0 ORDER BY parent_id, Menu_id ASC";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * Return the Second Level of the menu
     * @param int $menuid The unique ID of the Menu.
     * @return array
     */
    public function getSecond($menuid){
        $pdo = Logger::connect();
        $SQL = "SELECT * from Menu where parent_id = :menu_id ORDER BY parent_id, Menu_id ASC";
        $q = $pdo->prepare($SQL);
        $q->bindParam(':menu_id',$menuid);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * Return a list of all The Second level menus
     * @return array
     */
    public function listSecondLevel(){
        $pdo = Logger::connect();
        $SQL = "select * FROM menu m WHERE m.parent_id <> 0 and link_url = \"#\"";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * Return a list of all Permissions
     * @return array
     */
    public function listAllPermissions(){
        $pdo = Logger::connect();
        $SQL = "select * FROM permissions";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * Return a list of all Levels
     * @return array
     */
    public function listAllLevels(){
        $pdo = Logger::connect();
        $SQL = "select * FROM Level";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    /**
     * Return the Last level if the Level has access
     * @param int $level The level of the Administrator
     * @param int $menuid The unique ID of the Menu
     * @return array
     */
    public function getMenu($level,$menuid) {
        try{
            $pdo = Logger::connect();
            $AccesQ = "Select m1.*
                    from role_perm rp
                    join permissions p on rp.perm_id = p.perm_id
                    join menu m on rp.menu_id = m.Menu_id
                    join menu m1 on m1.parent_id = m.Menu_id
                    where p.permission = \"Read\" and rp.level= :level and m.menu_id = :menu_id";
            $stmnt = $pdo->prepare($AccesQ);
            $stmnt->bindParam(':level',$level);
            $stmnt->bindParam(':menu_id',$menuid);
//            print $AccesQ."<br>";
//            print "Level: ".$level." Menu_id: ".$menuid."<br>";
            $stmnt->execute();
            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        }  catch (PDOException $e){
            print $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will list all Roles and Permissions
     * @param string $order The order of sorting
     * @return array
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Level";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select rp.role_perm_id, Level, M.label Menu, Permission "
                . "from role_perm rp "
                . "join Menu m on rp.menu_id = m.Menu_id "
                . "join permissions p on rp.perm_id = p.perm_id order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will create a new Permission;
     * @param int $Level The Level of the administrator
     * @param int $menu the ID Level of the menu
     * @param int $permission the ID of the Permission
     * @param string $AdminName The name of the Administrator
     */
    public function create($Level,$menu,$permission,$AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into role_perm (Level, perm_id, menu_id) values (:level, :permid, :menu)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':level',$Level);
        $q->bindParam(':permid',$permission);
        $q->bindParam(':menu',$menu);
        if ($q->execute()){
            $PermValue = "Permission: ".$this->getPermission($permission)." added for Level: ".$Level." for Menu: ".$this->getMenuById($menu);
            $UUIDQ = "Select role_perm_id from role_perm order by role_perm_id desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            $this->logCreate(self::$table, $row["role_perm_id"], $PermValue, $AdminName);
        }
        Logger::disconnect();
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::activate()
	 */
    public function activate($UUID, $AdminName) {
    	throw new Exception("Access activation not implemented");
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::delete()
	 */
    public function delete($UUID, $reason, $AdminName) {
    	$pdo = Logger::connect();
    	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$sql = "delete from role_perm where role_perm_id = :uuid";
    	$q = $pdo->prepare($sql);
    	$q->bindParam(':uuid',$UUID);
    	if ($q->execute()){
    		//TODO: Log delete to a deleted object ??
    	}
    	Logger::disconnect();
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::selectById()
	 */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Level,m.Menu_id, M.label Menu, Permission, p.perm_id "
                . "from role_perm rp "
                . "join Menu m on rp.menu_id = m.Menu_id "
                . "join permissions p on rp.perm_id = p.perm_id where rp.role_perm_id = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return the permission from the given permission
     * @param int $permission The unique ID of the Permission
     * @return string
     */
    private function getPermission($permission){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select permission from permissions where perm_id = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$permission);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["permission"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will check if a given level, menu and permission exist
     * @param int $Level The level of the Administrator
     * @param int $menu The unique ID of the Menu. 
     * @param int $permission The id of the Permission
     * @return boolean
     */
    public function dubbelChecker($Level,$menu,$permission) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from role_perm where level = :level and perm_id = :permid and menu_id = :menu";
        $q = $pdo->prepare($sql);
        $q->bindParam(':level',$Level);
        $q->bindParam(':permid',$permission);
        $q->bindParam(':menu',$menu);
        $q->execute();
        if ($q->rowCount()>0){
            return TRUE;
        }  else {
            return FALSE;
        }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch()
     */
    public function selectBySearch($search) {
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select rp.role_perm_id, Level, M.label Menu, Permission "
                . "from role_perm rp "
                . "join Menu m on rp.menu_id = m.Menu_id "
                . "join permissions p on rp.perm_id = p.perm_id "
                . "where M.Label like :search or Permission like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Menu
     * @param int $menu The unique ID of the Menu.
     * @return string
     */
    private function getMenuById($menu){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Label from Menu where Menu_id = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$menu);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Label"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }

}
