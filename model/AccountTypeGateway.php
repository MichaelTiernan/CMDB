<?php
require_once 'Logger.php';

class AccountTypeGateway extends Logger{
    private static $table = 'accounttype';
    
    public function activate($UUID, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update AccountType set Active = 1, Deactivate_reason = NULL where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = "AccountType with ".  $this->getType($UUID)." ".  $this->getDescription($UUID);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }

    public function delete($UUID, $reason, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update AccountType set Active = 0, Deactivate_reason = :reason where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Value = "AccountType with ".  $this->getType($UUID)." ".  $this->getDescription($UUID);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
            print "UUID: ".$UUID." reason: ".$reason."<br>";
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will add a new AccountType
     * @param string $type
     * @param string $description
     * @param string $AdminName
     */
    public function create($type,$description, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Insert into AccountType (Type,Description) values (:type,:description)";
            $q = $pdo->prepare($sql);
            $q->bindParam(':type',$type);
            $q->bindParam(':description',$description);
            if ($q->execute()){
                $Value = "AccountType width Type: ".$type." and Description ".$description;
                $UUIDQ = "Select Type_ID from AcountType order by Type_ID desc limit 1";
                $stmnt = $pdo->prepare($UUIDQ);
                $stmnt->execute();
                $row = $stmnt->fetch(PDO::FETCH_ASSOC);
                Logger::logCreate(self::$table, $row["Type_ID"], $Value, $AdminName);
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Type";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from accounttype it order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * @see Logger::selectBySearch($search)
     */
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from accounttype it "
                . "where Type like :search or Description like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * Return all active AccountTypes
     * @return Array
     */
    public function getAllTypes() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description from accounttype where Active = 1";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            //print_r($q->fetchAll(PDO::FETCH_ASSOC));
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }

    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Type, Description, if(active=1,\"Active\",\"Inactive\") as Active from accounttype it where Type_ID= :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param Integer $UUID
     * @param String $type
     * @param String $description
     * @param String $AdminName
     */
    public function update($UUID,$type,$description,$AdminName){
        $OldType = $this->getType($UUID);
        $OldDescription = $this->getDescription($UUID);
        //Detect Changes
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if (strcasecmp($OldDescription, $description) != 0){
                $this->logUpdate(self::$table, $UUID, "Description", $OldDescription, $description, $AdminName);
                $sql =  "Update AccountType set Description = :description where Type_ID = :uuid" ;
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':description',$description);
                $q->execute();
            }
            if (strcasecmp($OldType,$type) != 0){
                $this->logUpdate(self::$table, $UUID, "Type", $OldType, $type, $AdminName);
                $sql =  "Update AccountType set Type = :Type where Type_ID = :uuid" ;
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':Type',$type);
                $q->execute();
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will check if the same Identity Type exist.
     * @param String $Type
     * @param String $Description
     * @return boolean
     * @throws PDOException
     */
    public function CheckDoubleEntry($Type,$Description) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select * from AccountType where Type =:Type and Description = :Description";
            $q = $pdo->prepare($sql);
            $q->bindParam(':Type',$Type);
            $q->bindParam(':Description',$Description);
            $q->execute();
            if ($q->rowCount()>0){
                return TRUE;
            }  else {
                return FALSE;
            }
        }  catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Type
     * @param Int $UUID
     * @return string
     */
    private function getType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from AccountType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Desription
     * @param Int $UUID
     * @return string
     */
    private function getDescription($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Description from AccountType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Description"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
