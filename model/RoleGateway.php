<?php
require_once 'Logger.php';
class RoleGateway extends Logger{
    private static $table = 'role';
    /**
     * 
     * @param Integer $UUID The Unique ID of the Role
     * @param String $AdminName
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update Rale Set Active = 1, Deactivate_reason= NULL where Role_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $Type = $this->getType($UUID);
            $Value = $Value = "Role width name: ".  $this->getName($UUID)." and Type: ".$this->getRoleType($Type);
            $this->logActivation(self::$table, $UUID, $Value, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param Integer $UUID The Unique ID of the Role
     * @param String $reason
     * @param String $AdminName
     */
    public function delete($UUID, $reason, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update Rale Set Active = 0, Deactivate_reason= :reason where Role_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':reason',$reason);
        if ($q->execute()){
            $Type = $this->getType($UUID);
            $Value = $Value = "Role width name: ".  $this->getName($UUID)." and Type: ".$this->getRoleType($Type);
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param string $order
     * @return type
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Type";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select r.Role_ID, r.Name, r.Description, if(r.active=1,\"Active\",\"Inactive\") as Active, rt.Type "
                . "from Role R "
                . "join roletype rt on r.Type = RT.Type_ID order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    public function selectBySearch($search){
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select r.Role_ID, r.Name, r.Description, if(r.active=1,\"Active\",\"Inactive\") as Active, rt.Type "
                . "from Role R "
                . "join roletype rt on r.Type = RT.Type_ID "
                . "where Name like :search or Description like :search or rt.Type like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param Integer $id The Unique ID of the Role
     * @return type
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select r.Name, r.Description, if(r.active=1,\"Active\",\"Inactive\") as Active,RT.Type_ID, rt.Type "
                . "from Role R "
                . "join roletype rt on r.Type = RT.Type_ID where r.Role_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will create a new Role
     * @param String $Name
     * @param String $Description
     * @param String $Type
     * @param String $AdminName
     */
    public function create($Name,$Description,$Type,$AdminName){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into Role (Name,Description, Type) values "
                . "(:name,:description,:type)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':name',$Name);
        $q->bindParam(':description',$Description);
        $q->bindParam(':type',$Type);
        if ($q->execute()){
            $Value = "Role width name: ".$Name." and Type: ".$this->getRoleType($Type);
            $UUIDQ = "Select Role_ID from Role order by Role_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            Logger::logCreate(self::$table, $row["Role_ID"], $Value, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * This function will update the given Role
     * @param Integer $UUID The Unique ID of the Role
     * @param String $Name
     * @param String $Description
     * @param Integer $Type
     * @param String $AdminName
     */
    public function update($UUID,$Name,$Description,$Type,$AdminName){
        $OldType = $this->getType($UUID);
        $OldRoleType = $this->getRoleType($OldType);
        $OldName = $this->getName($UUID);
        $OldDescription = $this->getDescription($UUID);
        //Detect changes
        if (!$OldType == $Type){
            $NewValue = $this->getRoleType($Type);
            $this->logUpdate(self::$table, $UUID, "Type", $OldRoleType, $NewValue, $AdminName); 
        }
        if (strcmp($OldName, $Name) != 0){
            $this->logUpdate(self::$table, $UUID, "Name", $OldName, $Name, $AdminName);
        }
        if (strcmp($OldDescription, $Description) != 0){
            $this->logUpdate(self::$table, $UUID, "Description", $OldDescription, $Description, $AdminName);
        }
        //Update
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Update Role set Name = :name, Description = :descrition, Type = :type where Role_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':name',$Name);
        $q->bindParam(':descrition',$Description);
        $q->bindParam(':type',$Type);
        $q->execute();
        Logger::disconnect();
    }
    /**
     * This function will check if the same Identity Type exist.
     * @param String $Type
     * @param String $Description
     * @return boolean
     * @throws PDOException
     */
    public function CheckDoubleEntry($Type,$Description,$Name) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select * from role where Type =:Type and Description = :Description and Name = :Name";
            $q = $pdo->prepare($sql);
            $q->bindParam(':Type',$Type);
            $q->bindParam(':Description',$Description);
            $q->bindParam(':Name',$Name);
            $q->execute();
            if ($q->rowCount()>0){
                return TRUE;
            }  else {
                return FALSE;
            }
        }  catch (PDOException $e){
            print $e;
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param Integer $UUID The Unique ID of the Role
     * @return string
     */
    private function getType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from Role where Role_ID = :uuid" ;
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
     * 
     * @param Integer $UUID The Unique ID of the Role
     * @return string 
     */
    private function getName($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Name from Role where Role_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Name"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Desription
     * @param Int $UUID The Unique ID of the Role
     * @return string
     */
    private function getDescription($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Description from role where Role_ID = :uuid" ;
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
    /**
     * This function will reurn the RoleType Type and description
     * @param Integer $TypeID The Unique ID of the RoleType
     * @return string
     */
    private function getRoleType($TypeID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from roletype where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$TypeID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
