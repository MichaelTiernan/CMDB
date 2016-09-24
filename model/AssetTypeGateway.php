<?php
require_once 'Logger.php';
class AssetTypeGateway extends Logger{
    private static $table = 'assettype';
    /**
     * This function will Activate the AssetType
     * @param Integer $UUID The Unique ID of the AssetType
     * @param String $AdminName
     * @throws PDOException
     */
    public function activate($UUID, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update AssetType set Active = 1, Deactivate_reason = NULL where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = $this->getCategory($UUID)."Type with ".  $this->getVendor($UUID)." ".  $this->getType($UUID);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will deactivate the AssetType
     * @param Integer $UUID The Unique ID of the AssetType
     * @param String $reason
     * @param String $AdminName
     * @throws PDOException
     */
    public function delete($UUID, $reason, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update AssetType set Active = 0, Deactivate_reason = :reason where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Value = $this->getCategory($UUID)."Type with ".  $this->getVendor($UUID)." ".  $this->getType($UUID);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
            print "UUID: ".$UUID." reason: ".$reason."<br>";
        }catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will return all know Asset Types
     * @param string $order
     * @return Array
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Category";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Vendor, Type, C.Category,if(at.active=1,\"Active\",\"Inactive\") as Active "
                . "from assettype at "
                . "join Category c on at.Category = c.ID order by ".$order;
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
        $sql = "Select Type_ID, Vendor, Type, C.Category,if(at.active=1,\"Active\",\"Inactive\") as Active "
                . "from assettype at "
                . "join Category c on at.Category = c.ID order by "
                . "where Vendor like :search or Type like :search or c.Category like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param type $Category
     * @param type $Vendor
     * @param type $Type
     * @param type $AdminName
     */
    public function create($Category,$Vendor,$Type,$AdminName){
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Insert into AssetType (Category, Vendor, Type) values (:cat,:vendor,:type)";
            $q = $pdo->prepare($sql);
            $q->bindParam(':type',$Type);
            $q->bindParam(':vendor',$Vendor);
            $q->bindParam(':cat',$Category);
            if ($q->execute()){
                $Value = $this->getCategoryByID($Category)."Type width Vendor: ".$Vendor." and Type ".$Type;
                $UUIDQ = "Select Type_ID from AssetType order by Type_ID desc limit 1";
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
    /**
     * 
     * @param Integer $UUID
     * @param Integer $Category
     * @param String $Vendor
     * @param String $Type
     * @param String $AdminName
     * @throws PDOException
     */
    public function update($UUID,$Category,$Vendor,$Type,$AdminName) {
        $OldCategory = $this->getCategory($UUID);
        $NewCategory = $this->getCategoryByID($Category);
        $OldVendor = $this->getVendor($UUID);
        $OldType = $this->getType($UUID);
        //Detect changes
        $Changed = FALSE;
        if (strcmp($OldCategory, $NewCategory) != 0){
            $Changed = TRUE;
            self::logUpdate(self::$table, $UUID, "Category",$OldCategory, $NewCategory, $AdminName);
        }
        if (strcmp($OldVendor, $Vendor) !=0){
            $Changed = TRUE;
            self::logUpdate(self::$table, $UUID, "Vendor",$OldVendor, $Vendor, $AdminName);
        }
        if (strcmp($OldType, $Type) !=0){
            $Changed = TRUE;
            self::logUpdate(self::$table, $UUID, "Type",$OldType, $Type, $AdminName);
        }
        //Update Database
        if ($Changed){
            try{
                $pdo = Logger::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "Update AssetType set Category = :cat, Vendor = :vendor, Type= :type where Type_id = :uuid";
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':cat',$Category);
                $q->bindParam(':type',$Type);
                $q->bindParam(':vendor',$Vendor);
                $q->execute();
            } catch (PDOException $ex){
                throw $ex;
            }
            Logger::disconnect();
        }
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Type_ID, Vendor, Type,C.ID Cat_ID, C.Category,if(at.active=1,\"Active\",\"Inactive\") as Active "
                . "from assettype at "
                . "join Category c on at.Category = c.ID where Type_ID= :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(":id", $id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will check if the same Asset Type exist.
     * @param String $Type
     * @param String $Description
     * @return boolean
     * @throws PDOException
     */
    public function CheckDoubleEntry($Category,$Vendor,$Type) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select * from AssetType where Type =:Type and Vendor = :vendor and Category = :cat";
            $q = $pdo->prepare($sql);
            $q->bindParam(':Type',$Type);
            $q->bindParam(':vendor',$Vendor);
            $q->bindParam(':cat',$Category);
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
     * This function will return all active Categories
     * @return type
     */
    public function getAllCategories() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select ID, Category from Category where Active = 1";
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
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
        $sql =  "Select Type from AssetType where Type_ID = :uuid" ;
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
     * This function will return the Vendor
     * @param Int $UUID
     * @return string
     */
    private function getVendor($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Vendor from AssetType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Vendor"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Category
     * @param Int $UUID The Unique ID of the AssetType
     * @return string
     */
    private function getCategory($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select c.Category from AssetType at join Category c on at.Category = c.ID where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Category"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Category
     * @param Int $CatID The Unique ID of the Category
     * @return string
     */
    private function getCategoryByID($CatID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select c.Category from Category c where ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$CatID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Category"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
