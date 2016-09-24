<?php
require_once 'Logger.php';
class TokenGateway extends Logger{
    private static $table = 'token';
    /**
     * This function will activate the given token
     * @param String $UUID The AssetTag of the Token
     * @param String $AdminName The name of the person who did the activation
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update Asset set Active = 1, Deactivate_reason = NULL where AssetTag = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $Value = "Token with AssetTag: ".$UUID." and Type: ".$this->getType($UUID);
            $this->logActivation(self::$table, $UUID, $Value, $AdminName);
        }
    }
    /**
     * This function will deactivate the given Token
     * @param String $UUID The AssetTag of the token
     * @param String $reason The Reason why the deactivation was done
     * @param String $AdminName The name of the person who did the activation
     */
    public function delete($UUID, $reason, $AdminName) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Update Asset set Active = 0, Deactivate_reason = :reason where AssetTag = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':reason',$reason);
        if ($q->execute()){
            $Value = "Token with AssetTag: ".$UUID." and Type: ".$this->getType($UUID);
            $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
        }
    }
    /**
     * This function will sellect all Tokens
     * @param string $order The name of the column to sort on
     * @return Array
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "AssetTag";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AssetTag, SerialNumber, CONCAT(at.Vendor,\" \",at.Type) Type, if(a.active=1,\"Active\",\"Inactive\") as Active,"
                . "IFNULL(i.Name,\"Not in use\") ussage "
                . "from asset a "
                . "join assettype at on a.type = at.type_id "
                . "join category c on a.Category = c.ID and c.Category= \"Token\""
                . "left join identity i on a.identity = i.Iden_id order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return all the info for one given Token
     * @param string $id The AssetTag of the Token
     * @return Array
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AssetTag, SerialNumber, a.type as Type_ID, CONCAT(at.Vendor,\" \",at.Type) Type, if(a.active=1,\"Active\",\"Inactive\") as Active,"
                . "IFNULL(i.Name,\"Not in use\") ussage "
                . "from asset a "
                . "join assettype at on a.type = at.type_id "
                . "join category c on a.Category = c.ID and c.Category= \"Token\""
                . "left join identity i on a.identity = i.Iden_id where AssetTag = :uuid";
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will return all the Tokens that contains a given value
     * @param string $search The search criteria
     * @return Array
     */
    public function selectBySearch($search) {
        $searhterm = "%$search%";
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select AssetTag, SerialNumber, CONCAT(at.Vendor,\" \",at.Type) Type, if(a.active=1,\"Active\",\"Inactive\") as Active,"
                . "IFNULL(i.Name,\"Not in use\") ussage "
                . "from asset a "
                . "join assettype at on a.type = at.type_id "
                . "join category c on a.Category = c.ID and c.Category= \"Token\""
                . "left join identity i on a.identity = i.Iden_id "
                . "where AssetTag like :search or at.Vendor like :search or at.Type like :search or i.name like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will list All the Token Types
     * @return Array
     */
    public function listAllTokenCategories(){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Vendor, Type "
                . "from assettype at "
                . "join Category c on at.Category = C.ID "
                . "where at.Active=1 and C.Category = \"Token\"";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will check if the given serial number is unique
     * @param string $serialNumber The serial number
     * @return boolean
     */
    public function isSerialUnique($serialNumber) {
        $result = true;
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select serialnumber "
                . "from asset at "
                . "join Category c on at.Category = C.ID "
                . "where C.Category = \"Token\" and SerialNumber = :serial";
        $q = $pdo->prepare($sql);
        $q->bindParam(':serial',$serialNumber);
        $q->execute();
        if ($q->rowCount()>0){
            $result = FALSE;
        }
        Logger::disconnect();
        return $result;
    }
    /**
     * This function will create a new Token
     * @param type $AssetTag
     * @param type $serialNumber
     * @param type $type
     * @param type $AdminName
     */    
    public function create($AssetTag,$serialNumber,$type,$AdminName) {
        $TokenId = $this->getCategoryID("Token");
        //print_r("TokenID: ".$TokenId);
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into Asset (AssetTag,SerialNumber,Type,Category) values (:AssetTag,:SerialNumber,:Type,:Category)";
        $q = $pdo->prepare($sql);
        $q->bindParam(':AssetTag',$AssetTag);
        $q->bindParam(':SerialNumber',$serialNumber);
        $q->bindParam(':Type',$type);
        $q->bindParam(':Category',$TokenId);
        if ($q->execute()){
            $Value = "Token with Asset: ".$AssetTag." and SerialNumber: ".$serialNumber." and type: ".$this->getTypeByID($type);
            $this->logCreate(self::$table, $AssetTag, $Value, $AdminName);
        }
        Logger::disconnect();
    }
    /**
     * This function will return the assigned Identity
     * @param string $assetTag The AssetTag of the token
     * @return Array
     */
    public function listOfAssignedIdentities($assetTag){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select i.Name, i.UserID from Identity i "
                . "join Asset a on a.Identity = i.Iden_Id where a.AssetTag = :assettag";
        $q = $pdo->prepare($sql);
        $q->bindParam(':assettag',$assetTag); 
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC);
        }
        Logger::disconnect();
    }
    
    public function update($assetTag, $serialNumber, $type, $AdminName){
        $OldSerialNumber = $this->getSerialNumber($assetTag);
        $OldType = $this->getType($assetTag);
        $NewType = $this->getTypeByID($type);
        //Detect changes
        $Changes = FALSE;
        if (strcmp($OldSerialNumber, $serialNumber) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $assetTag, "SerialNumber", $OldSerialNumber, $serialNumber, $AdminName);
        }
        if (strcmp($OldType, $NewType) != 0){
            $Changes = TRUE;
            self::logUpdate(self::$table, $assetTag, "Type", $OldType, $NewType, $AdminName);
        }
        if ($Changes){
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Asset set SerialNumber = :serial, Type= :type "
                    . "where AssetTag = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$assetTag);
            $q->bindParam(':type',$type);
            $q->bindParam(':serial',$serialNumber);
            $q->execute();
            Logger::disconnect();
        }
    }
    /**
     * This function will set the Category ID.
     * @param String $category
     * @return Int The ID of the given category
     */
    private function getCategoryID($category){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select ID From Category where Category = :cat";
        $q = $pdo->prepare($sql);
        $q->bindParam(':cat',$category); 
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC); 
            return $row["ID"];
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
}
