<?php
require_once 'Logger.php';
class Identity extends Logger{
    private static $table = 'identity';

    public function create($FirstName,$LastName,$UserID,$type,$AdminName) {
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
            Identity::logCreate(self::$table, $UUID, $Value, $AdminName);
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

//put your code here
}
