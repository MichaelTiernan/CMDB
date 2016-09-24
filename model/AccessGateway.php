<?php
require_once 'Logger.php';
class AccessGateway extends Logger{
    private static $table = 'role_perm';
    /**
     * Check if the level has access to the requested source
     * @param Integer $level
     * @param String $sitePart
     * @param String $action
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
     * @return Array
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
     * @param type $menuid
     * @return Array
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
     * @return type
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
     * @return type
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
     * @return type
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
     * Retunr the Last level if the Level has access
     * @param Integer $level
     * @param Integer $menuid
     * @return Array
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
     * This function will list all Accounts
     * @param string $order The order of sorting
     * @return Array
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
     * This function will create a new Permmission;
     * @param type $Level
     * @param type $menu
     * @param type $permission
     * @param type $AdminName
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

    public function activate($UUID, $AdminName) {
        
    }

    public function delete($UUID, $reason, $AdminName) {
        
    }

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
     * 
     * @param type $permission
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
     * 
     * @param type $Level
     * @param type $menu
     * @param type $permission
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
     * 
     * @param type $menu
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
