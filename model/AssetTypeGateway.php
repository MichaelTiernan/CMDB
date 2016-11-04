<?php
require_once 'Logger.php';
class AssetTypeGateway extends Logger{
	/**
	 * This variable will keep the table for the logging
	 * @var string
	 */
    private static $table = 'assettype';
    /**
     * {@inheritDoc}
     * @see Logger::activate()
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update AssetType set Active = 1, Deactivate_reason = NULL where Type_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = $this->getCategory($UUID)."Type with ".  $this->getVendor($UUID)." ".  $this->getType($UUID);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::delete()
     */
    public function delete($UUID, $reason, $AdminName) {
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
        Logger::disconnect();
    }
    /**
     * This function will return all know Asset Types
     * @param string $order
     * @return array
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
     * {@inheritDoc}
     * @see Logger::selectBySearch()
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
     * This function will create a new Category
     * @param int $Category The Unique ID of the asset category
     * @param string $Vendor The name of the vendor
     * @param string $Type The name of the type
     * @param string $AdminName The name of the administrator that did the creation
     */
    public function create($Category,$Vendor,$Type,$AdminName){
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
        Logger::disconnect();
    }
    /**
     * This function will update a given AssetType
     * @param integer $UUID The unique id of the asset type
     * @param integer $Category The unique ID of the asset category 
     * @param string $Vendor The name of the vendor
     * @param string $Type the name of the type
     * @param string $AdminName The name of the administrator that did the creation
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
            $pdo = Logger::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "Update AssetType set Category = :cat, Vendor = :vendor, Type= :type where Type_id = :uuid";
                $q = $pdo->prepare($sql);
                $q->bindParam(':uuid',$UUID);
                $q->bindParam(':cat',$Category);
                $q->bindParam(':type',$Type);
                $q->bindParam(':vendor',$Vendor);
                $q->execute();
            Logger::disconnect();
        }
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
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
     * @param int $Category The unique id of the category
     * @param string $Type The name of the type
     * @param string $Vendor The name of the vendor
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
     * @return array
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
     * @param int $UUID the unique ID of the Asset type
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
     * @param int $UUID the unique ID of the Asset type
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
     * @param int $UUID The Unique ID of the AssetType
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
     * @param int $CatID The Unique ID of the Category
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
