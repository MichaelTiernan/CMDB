<?php
require_once 'Logger.php';
class DeviceGateway extends Logger{
    private $Category ;
    private static $table = "devices";
    
    public function activate($UUID, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Asset set Active = 1, Deactivate_reason = NULL where AssetTag = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = $this->getCategory($this->Category)." with AssetTag: ".$UUID." and Type: ".$this->getType($UUID);
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
            $sql = "Update Asset set Active = 0, Deactivate_reason = :reason where AssetTag = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Value = $this->getCategory($this->Category)." with AssetTag: ".$UUID." and Type: ".$this->getType($UUID);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
//            print "UUID: ".$UUID." reason: ".$reason."<br>";
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param type $AssetTag
     * @param type $SerialNumber
     * @param type $Type
     * @param type $RAM
     * @param type $IP
     * @param type $Name
     * @param type $MAC
     * @param type $AdminName
     */
    public function create($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Insert into Asset (AssetTag, SerialNumber, IP_Adress, Name, MAC, RAM, Type, Category) "
                . "values (:assettag, :serialnumber, :IP, :name, :mac, :ram, :type, :cat)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':assettag',$AssetTag); 
        $q->bindParam(':cat',  $this->Category); 
        $q->bindParam(':serialnumber',$SerialNumber); 
        $q->bindParam(':IP',$IP); 
        $q->bindParam(':name',$Name); 
        $q->bindParam(':mac',$MAC); 
        $q->bindParam(':type',$Type); 
        $q->bindParam(':ram',$RAM); 
        if ($q->execute()){
            $Value = $this->getCategory($this->Category)." with AssetTag: ".$AssetTag." and Type: ".$this->getTypeByID($Type);
            $this->logCreate(self::$table, $AssetTag, $Value, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * This function will update an Asset
     * @param type $AssetTag
     * @param type $SerialNumber
     * @param type $Type
     * @param type $RAM
     * @param type $IP
     * @param type $Name
     * @param type $MAC
     * @param type $AdminName
     * @throws PDOException
     */
    public function update($AssetTag,$SerialNumber,$Type,$RAM,$IP,$Name,$MAC,$AdminName){
        $OldSerialNumber = $this->getSerialNumber($AssetTag);
        $OldType = $this->getType($AssetTag);
        $NewType = $this->getTypeByID($Type);
        $OldRAM = $this->getRAM($AssetTag);
        $OldIP = $this->getIPAdress($AssetTag);
        $OldName = $this->getName($AssetTag);
        $OldMAC = $this->getMAC($AssetTag);
        //Detect changes
        $Changes = FALSE;
        if (strcmp($OldSerialNumber, $SerialNumber) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "SerialNumber",$OldSerialNumber, $SerialNumber, $AdminName);
        }
        if (strcmp($OldType, $NewType) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "Type",$OldType, $NewType, $AdminName);
        }
        if (strcmp($OldRAM, $RAM) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "RAM",$OldRAM, $RAM, $AdminName);
        }
        if (strcmp($OldName, $Name) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "Name",$OldName, $Name, $AdminName);
        }
        if (strcmp($OldIP, $IP) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "IP",$OldIP, $IP, $AdminName);
        }
        if (strcmp($OldMAC, $MAC) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $AssetTag, "MAC",$OldMAC, $MAC, $AdminName);
        }
        if ($Changes){
            //Update
            try{
                $pdo = Logger::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "Update Asset set SerialNumber = :serial, Type= :type, "
                        . "IP_Adress = :ip, MAC = :MAC, RAM = :RAM, Name = :name "
                        . " where AssetTag = :uuid";
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$AssetTag);
                $q->bindParam(':type',$Type);
                $q->bindParam(':serial',$SerialNumber);
                $q->bindParam(':ip',$IP);
                $q->bindParam(':MAC',$MAC);
                $q->bindParam(':RAM',$RAM);
                $q->bindParam(':Name',$Name);
                $q->execute();
            } catch (PDOException $ex){
                throw $ex;
            }
            Logger::disconnect();
        }
    }
    /**
     * 
     * @param string $order
     * @return type
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "AssetTag";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AssetTag, SerialNumber, CONCAT(at.Vendor,\" \",at.Type) Type, "
                . "if(a.active=1,\"Active\",\"Inactive\") Active, "
                . "IFNULL(i.Name,\"Not in use\") ussage " 
                . "from Asset a join assettype at on a.Type = at.Type_id "
                . "left join identity i on a.Identity = i.Iden_ID order by ".$order;
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
        $sql = "Select AssetTag, SerialNumber, CONCAT(at.Vendor,\" \",at.Type) Type, "
                . "if(a.active=1,\"Active\",\"Inactive\") Active, "
                . "IFNULL(i.Name,\"Not in use\") ussage " 
                . "from Asset a join assettype at on a.Type = at.Type_id "
                . "left join identity i on a.Identity = i.Iden_ID "
                . "where AssetTag like :search or at.Vendor like :search or at.type like :search "
                . "or i.name like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param String $id
     * @return type
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AssetTag, SerialNumber,at.Type_ID, CONCAT(at.Vendor,\" \",at.Type) Type, "
                . "if(a.active=1,\"Active\",\"Inactive\") Active, "
                . "MAC, Name, IP_Adress, RAM " 
                . "from Asset a join assettype at on a.Type = at.Type_id "
                . "where A.AssetTag = :UUID";
        $q = $pdo->prepare($sql);
        $q->bindParam(':UUID',$id, PDO::PARAM_STR);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will set the Category ID.
     * @param String $category
     */
    public function setCategory($category){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select ID From Category where Category = :cat";
        $q = $pdo->prepare($sql);
        $q->bindParam(':cat',$category); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            $this->Category = $row["ID"];
        }
        Logger::disconnect();
    }
    /**
     * This function will list all AssetTypes for a given Category.
     * @param String $Category The category
     * @return Array
     */
    public function listAllTypes($Category){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Vendor, Type "
                . "from assettype at "
                . "join category c on at.Category = c.ID "
                . "where c.Category = :cat";
        $q = $pdo->prepare($sql);
        $q->bindParam(':cat',$Category); 
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * Thius function will list all Ram's
     * @return Array
     */
    public function listAllRams(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Text from RAM";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * this function will return the Category for a given Category ID
     * @param Int $CatID The unique category ID
     * @return string
     */
    private function getCategory($CatID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Category From Category where ID = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$CatID); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Category"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the SerialNumber
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getSerialNumber($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select SerialNumber From Asset where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["SerialNumber"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the MAC
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getMAC($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select MAC From Asset where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["MAC"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the IP Adress
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getIPAdress($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select IP_Adress From Asset where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["IP_Adress"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Name
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getName($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Name From Asset where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Name"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the RAM
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getRAM($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select RAM From Asset where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["RAM"];
        }else{
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Type
     * @param string $AssetTag The unique AssetTag
     * @return string
     */
    private function getType($AssetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select CONCAT(at.Vendor,\" \",at.Type) Type "
                . "From Asset a join AssetType at on a.Type = at.Type_ID where AssetTag = :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$AssetTag); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["Type"];
        }else{
            return "";
        }
        Logger::disconnect();
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
}
