<?php
require_once 'Logger.php';
//require_once 'Database.php';

class IdentityGateway extends Logger{
    private static $table = 'identity';
    
    public function create($FirstName,$LastName,$UserID,$type,$AdminName) {
        $UserID = ($UserID!= NULL)?$UserID:NULL;
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO Identity (FirstName,LastName,UserID,Type) values(:firstname, :lastname, :userid, :type)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':firstname',$FirstName);
        $q->bindParam(':lastname',$LastName);
        $q->bindParam(':userid',$UserID);
        $q->bindParam(':type',$type);
        if ($q->execute()){
            $Value = "Identity width name: ".$FirstName." ".$LastName;
            $UUID = 1;
            Logger::logCreate(self::$table, $UUID, $Value, $AdminName);
        }       
        Logger::disconnect();
    }

    public function delete() {
        
    }

    public function details() {
        
    }

    public function read() {
        
    }

    public function update() {
        
    }
    
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Name";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Name, UserID, E_Mail, Language, it.Type, if(i.active=1,\"Active\",\"Inactive\") as Active "
                . "from Identity i join identitytype it on i.type = it.type_id order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
}
