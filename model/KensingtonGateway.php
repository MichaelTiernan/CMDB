<?php
require_once 'Logger.php';
class KensingtonGateway extends Logger{
    private static $table = 'kensington';
    /**
     * {@inheritDoc}
     * @see Logger::activate()
     */
    public function activate($UUID, $AdminName) {
    $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update Kensington set Active = 1, Deactivate_reason = NULL where Key_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid', $UUID);
        if ($q->execute()){
        	$Value = "Kensington with type: ".$this->getType($UUID)." and have seral number: ".$this->getSerialNumber($UUID);
        	$this->logActivation($this::$table, $UUID, $Value, $AdminName);
        }
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::delete()
	 */
    public function delete($UUID, $reason, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update Kensington set Active = 0, Deactivate_reason = :reason where Key_ID = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':reason', $reason);
        $q->bindParam(':uuid', $UUID);
        if ($q->execute()){
        	$Value = "Kensington with type: ".$this->getType($UUID)." and have seral number: ".$this->getSerialNumber($UUID);
        	$this->logDelete($this::$table, $UUID, $Value, $reason, $AdminName);
        }
    }
    /**
     * This Function will create a new Kensington
     * @param int $Type The ID of the Kensington Type
     * @param string $Serial The Serial number of the kensington
     * @param int $NrKeys The amount of Keys
     * @param int $hasLock Indicates of the Keys has a Lock
     * @param string $AdminName The name that do the creation
     */
    public function create($Type,$Serial,$NrKeys,$hasLock,$AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into Kensington (Type, Serial, AmountKeys, hasLock) values (:type,:serial,:keys,:hasLock)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':type', $Type);
        $q->bindParam(':serial', $Serial);
        $q->bindParam(':keys', $NrKeys);
        $q->bindParam(':hasLock', $hasLock);
        if ($q->execute()){
            $Value = "Kensington created with type: ".$this->getTypeByID($Type)." and have seral number: ".$Serial;
            $UUIDQ = "Select Key_ID from Kensington order by Key_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            $this->logActivation(self::$table, $row["Key_ID"], $Value, $AdminName);
        }
    }
    /**
     * This function will update a given Kensington
     * @param int $UUID The unique ID of the Kensington
     * @param int $Type The ID of the Kensington Type
     * @param string $Serial The Serial number of the Kensington
     * @param int $NrKeys the amount of keys
     * @param int $hasLock the indication if the key has a lock
     * @param string $AdminName The Person who did the activation
     */
    public function update($UUID,$Type,$Serial,$NrKeys,$hasLock,$AdminName) {
        $oldType = $this->getType($UUID);
        $newType = $this->getTypeByID($Type);
        $OldSerial = $this->getSerialNumber($UUID);
        $OldNrKey = $this->getAmountKeys($UUID);
        $OldHasKey = $this->getHasLock($UUID);
        //Detect changes
        $Changes = FALSE;
        if (strcmp($OldSerial, $Serial) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $UUID, "SerialNumber",$OldSerial, $Serial, $AdminName);
        }
        if (strcmp($oldType, $newType) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $UUID, "Type",$oldType, $newType, $AdminName);
        }
        if ($OldNrKey <> $NrKeys){
            $Changes = TRUE;
            self::logUpdate(self::$table, $UUID, "Amount of Keys",$OldNrKey, $NrKeys, $AdminName);
        }
        if ($OldHasKey <> $hasLock){
            $OldHasKey = $OldHasKey == 1 ? "Yes" : "No";
            $hasLock = $hasLock == 1 ? "Yes" : "No";
            $Changes = TRUE;
            self::logUpdate(self::$table, $UUID, "has lock",$OldHasKey, $hasLock, $AdminName);
        }
        if ($Changes){
            $hasLock = $hasLock == "Yes" ? 1 : 0;
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "update kensington set Type=:type, Serial=:serial, hasLock=:hasLock, "
                . "AmountKeys=:keys where Key_Id =:id";
            $q = $pdo->prepare($sql);
            $q->bindParam(':type',$Type);
            $q->bindParam(':serial',$Serial);
            $q->bindParam(':hasLock',$hasLock);
            $q->bindParam(':keys',$NrKeys);
            $q->bindParam(':id',$UUID);
            $q->execute();
        }
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectAll()
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Serial";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Key_Id, Serial, AmountKeys, CONCAT(at.Vendor,\" \",at.Type) as Type, "
                . "if(k.active=1,\"Active\",\"Inactive\") as Active, "
                . "if(k.hasLock=1,\"Yes\",\"No\") as hasLock "
                . "from Kensington k "
                . "join Assettype at on k.Type = at.Type_id "
                . "order by ".$order;
        //echo "query: ".$sql."<br>";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will select all the fields fot a given Kensington Key
     * @param Integer $id The unique ID of the Kensington
     * @return Array
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Key_Id, Serial, AmountKeys, at.Type_ID, CONCAT(at.Vendor,\" \",at.Type) as Type, "
                . "if(k.active=1,\"Active\",\"Inactive\") as Active, "
                . "hasLock "
                . "from Kensington k "
                . "join Assettype at on k.Type = at.Type_id where Key_Id = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return the list of all kensingtons that have the search term
     * @param string $search The search term
     * @return Array
     */
    public function selectBySearch($search) {
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Key_Id, Serial, AmountKeys, at.Type_ID, CONCAT(at.Vendor,\" \",at.Type) as Type, "
                . "if(k.active=1,\"Active\",\"Inactive\") as Active, "
                . "if(k.hasLock=1,\"Yes\",\"No\") as hasLock "
                . "from Kensington k "
                . "join Assettype at on k.Type = at.Type_id "
                . "where serial like :search or at.Vendor like :search or at.Type or :search";
        //echo "query: ".$sql."<br>";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will list all the active Token Types.
     * @return Array
     */
    public function listAllTypes() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select CONCAT(at.Vendor,\" \",at.Type) as Type, at.Type_ID "
                . "from Assettype at "
                . "join Category c on at.Category = c.ID "
                . "where c.Category = \"Kensington\" and at.Active = 1";
        //echo "query: ".$sql."<br>";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will check if there is an other kensington exist and return TRUE if there is
     * @param int $Type The ID of the Kensington type
     * @param string $Serial The Serial Number of the Kensington
     * @return boolean
     */
    public function isUnique($Type,$Serial, $UUID = 0){
        if ($UUID > 0){
            $OldSerial = $this->getSerialNumber($UUID);
            $oldType = $this->getType($UUID);
            $newType = $this->getTypeByID($Type);
            if (strcmp($OldSerial, $Serial) != 0 or strcmp($oldType, $newType) != 0){
                $result = FALSE;
                $pdo = Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "Select Serial, Type "
                        . "from Kensington "
                        . "where Serial = :serial and Type = :type";
                $q = $pdo->prepare($sql);
                $q->bindParam(':serial',$Serial);
                $q->bindParam(':type',$Type);
                $q->execute();
                if ($q->rowCount()>0){
                    $result = TRUE;
                }
                Database::disconnect();
                return $result;
            }
        }else {
            $result = FALSE;
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select Serial, Type "
                    . "from Kensington "
                    . "where Serial = :serial and Type = :type";
            $q = $pdo->prepare($sql);
            $q->bindParam(':serial',$Serial);
            $q->bindParam(':type',$Type);
            $q->execute();
            if ($q->rowCount()>0){
                $result = TRUE;
            }
            Database::disconnect();
            return $result;
        }
    }
    /**
     * This function will return the Asset Info from a givven Kensington
     * @param int $UUID
     * @return array
     */
    public function GetAssetInfo($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select a.AssetTag, c.Category, CONCAT(at.Vendor,\" \",at.Type) Type, SerialNumber "
                . "From asset a "
                . "join Kensington k on k.AssetTag = a.AssetTag "
                . "join AssetType at on a.Type = at.Type_ID "
                . "join category c on a.Category = c.ID "
                . "where k.Key_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$UUID); 
        if ($q->execute()){
             return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
    }
    /**
     * This function will return the Asset Type for a given Asset Type ID
     * @param int $Type_ID
     * @return string
     */
    private function getTypeByID($Type_ID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select CONCAT(at.Vendor,\" \",at.Type) Type "
                . "From AssetType at where Type_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$Type_ID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Type"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the SerialNumber
     * @param int $UUID The Unique ID of the Kensington
     * @return string
     */
    private function getSerialNumber($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Serial From Kensington where Key_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$UUID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Serial"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Amount of keys
     * @param int $UUID The Unique ID of the Kensington
     * @return int
     */
    private function getAmountKeys($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AmountKeys From Kensington where Key_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$UUID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["AmountKeys"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the HasLock
     * @param int $UUID The Unique ID of the Kensington
     * @return int
     */
    private function getHasLock($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select hasLock From Kensington where Key_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$UUID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["hasLock"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Type
     * @param string $UUID The unique AssetTag
     * @return string
     */
    private function getType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select CONCAT(at.Vendor,\" \",at.Type) Type "
                . "From Kensington a join AssetType at on a.Type = at.Type_ID where Key_ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$UUID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Type"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
}
