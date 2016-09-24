<?php
require_once 'Logger.php';

class AccountGateway extends Logger{
    private static $table = 'account';
    /**
     * This function will activate the given Account
     * @param Integer $UUID The ID of the account
     * @param String $AdminName The name of the Admin that did the activation
     */
    public function activate($UUID, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Account set Active = 1, Deactivate_reason = NULL where Acc_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Type = $this->getType($UUID);
                $Application = $this->getApplication($UUID);
                $UserID = $this->getUserID($UUID);
                $Value = "Account with ". $UserID. " and Type: ".$this->getAccountType($Type)." for application: ".$this->getApplicationName($Application);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        }catch (PDOException $e){
            print $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will deactivate the given Account
     * @param Integer $UUID The ID of the Account
     * @param String $reason The reason the deactivation was done
     * @param String $AdminName The name of the Admin that did the activation
     */
    public function delete($UUID, $reason, $AdminName) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Account set Active = 0, Deactivate_reason = :reason where Acc_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Type = $this->getType($UUID);
                $Application = $this->getApplication($UUID);
                $UserID = $this->getUserID($UUID);
                $Value = "Account with ". $UserID. " and Type: ".$this->getAccountType($Type)." for application: ".$this->getApplicationName($Application);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
        }catch (PDOException $e){
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
            $order = "UserID";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select a.Acc_ID,UserID, AT.Type, ap.Name Application, if(A.active=1,\"Active\",\"Inactive\") as Active "
                . "from Account A join AccountType AT on A.type = AT.Type_ID "
                . "join Application ap on A.Application = ap.App_ID order by ".$order;
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
        $sql = "Select a.Acc_ID,UserID, AT.Type, ap.Name Application, if(A.active=1,\"Active\",\"Inactive\") as Active "
                . "from Account A join AccountType AT on A.type = AT.Type_ID "
                . "join Application ap on A.Application = ap.App_ID "
                . "where UserID like :search or ap.Name like :search or at.type like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This will create the Account
     * @param String $UserID The ID of the Account
     * @param Integer $Type The TypeID of the AccountType
     * @param Integer $Application The ApplicationID of the Application 
     * @param String $AdminName The name of the Admin that did the create
     */
    public function create($UserID,$Type,$Application,$AdminName){
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Insert into Account (UserID, Type, Application) Values (:userid,:type,:app)";
            $q = $pdo->prepare($sql);
            $q->bindParam(':userid',$UserID);
            $q->bindParam(':type',$Type);
            $q->bindParam(':app',$Application);
            if ($q->execute()){
                $Value = "Account with ". $UserID. " and Type: ".$this->getAccountType($Type)." for application: ".$this->getApplicationName($Application);
                $UUIDQ = "Select Acc_ID from Account order by Acc_ID desc limit 1";
                $stmnt = $pdo->prepare($UUIDQ);
                $stmnt->execute();
                $row = $stmnt->fetch(PDO::FETCH_ASSOC);
                $this->logCreate(self::$table, $row["Acc_ID"], $Value, $AdminName);
            }
        }catch (PDOException $e){
            print $e;
        }
        Logger::disconnect();
    }
    /**
     * This function will update the Account
     * @param Integer $UUID The ID of the Account
     * @param String $UserID
     * @param Integer $Type The TypeID of the AccountType
     * @param Integer $Application The ApplicationID of the Application
     * @param String $AdminName The name of the Admin that did the update
     */
    public function update($UUID,$UserID,$Type,$Application,$AdminName){
        $OldUserID = $this->getUserID($UUID);
        $OldType = $this->getType($UUID);
        $OldTypeName = $this->getAccountType($OldType);
        $OldApplication = $this->getType($UUID);
        $OldAppName = $this->getApplicationName($OldApplication);
        $NewAppName = $this->getApplicationName($Application);
        $NewTypeName = $this->getAccountType($Type);
        if (strcmp($OldUserID, $UserID) != 0){
            $this->logUpdate(self::$table, $UUID, "UserID", $OldUserID, $UserID, $AdminName);
        }
        if (strcmp($OldAppName,$NewAppName) != 0){
            $this->logUpdate(self::$table, $UUID, "Application", $OldAppName, $NewAppName, $AdminName);
        }
        if (strcmp($OldTypeName, $NewTypeName) != 0){
            $this->logUpdate(self::$table, $UUID, "Type", $OldTypeName, $NewTypeName, $AdminName);
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Update Account set UserID = :userid, Type = :type, Application= :application  where Acc_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':type',$Type);
        $q->bindParam(':application',$Application);
        $q->bindParam(':userid',$UserID);
        $q->execute();
        Logger::disconnect();
    }
    /**
     * This function will check if there is a double entry in the DB.
     * @param String $UserID The UserID of the application
     * @param Integer $Application The ApplicationID of the application
     * @return boolean
     */
    public function CheckDoubleEntry($UserID,$Application){
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select * from Account where UserID =:userid and Application = :Application";
            $q = $pdo->prepare($sql);
            $q->bindParam(':userid',$UserID);
            $q->bindParam(':Application',$Application);
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
     * @return type
     */
    public function getAllAcounts() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $SQL = "select a.`Acc_ID`,a.`UserID`,app.`Name` Application "
            ."from account a " 
            ."join application app on a.`Application` = app.`App_ID` "
            ."where a.`Acc_ID` not in (select Account from idenaccount ia where now() between ia.`ValidFrom` and ia.`ValidEnd`) "
            ."and a.`Active` = 1";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    
    public function AssignIdentity($UUID,$Identity,$From,$Until,$AdminName){
        if (empty($Until)){
            $newUntilDate = NULL;
        }else{
            $newUntilDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$Until);
        }
        $newFromDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$From);
        //$FromDate = date('Y-M-D H:i:s',strtotime($From));
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Insert into idenaccount (Identity, Account, ValidFrom, ValidEnd) "
                . "values (:account , :uuid, :from, :until)" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':account',$Identity);
        $q->bindParam(':from',$newFromDate);
        $q->bindParam(':until',$newUntilDate);
        if ($q->execute()){
            require_once 'IdentityGateway.php';
            $iden = new IdentityGateway();
            $AppID = $this->getApplication($UUID);
            $IdenValue = "Identity with Name ".$iden->getFirstName($Identity)." ".$iden->getLastName($Identity);
            $AccountValue = "Account with UserID ".$this->getUserID($UUID)." in Application ".$this->getApplicationName($AppID);
            $this->logAssignIden2Account("identity", $Identity, $IdenValue, $AccountValue, $AdminName);
            $this->logAssignAccount2Iden(self::$table, $UUID, $IdenValue, $AccountValue, $AdminName);
        }
    }

    /**
     * Thid function will return the info for one Account
     * @param Integer $id The ID of the account
     * @return Array
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select a.Acc_ID,UserID, AT.Type, ap.Name Application,ap.App_ID,AT.Type_ID,  if(A.active=1,\"Active\",\"Inactive\") as Active "
                . "from Account A join AccountType AT on A.type = AT.Type_ID "
                . "join Application ap on A.Application = ap.App_ID where Acc_ID= :id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * 
     * @param type $UUID
     * @return type
     */
    public function listAllIdentities($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $SQL = "select i.`Iden_ID`, i.`UserID`, i.Name, ia.ValidFrom, ia.ValidEnd "
            ."from Identity i "
            ."join idenaccount ia on ia.Identity = i.Iden_ID "
            ."where ia.Account = :uuid";
        $q = $pdo->prepare($SQL);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }

    /**
     * This function will return the name of an given AccountType.
     * @param Integer $AccountType The ID of the AccountType
     * @return string
    */
    private function getAccountType($AccountType){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from AccountType where Type_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$AccountType);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Type"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Name of the application for a given Application
     * @param Integer $Application The ID of the application
     * @return strings
     */
    public function getApplicationName($Application){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Name from Application where App_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$Application);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Name"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This will return the ApplicationType ID fromt the given Account
     * @param Integer $UUID The ID of the Account
     * @return string
     */
    private function getType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Type from Account where Acc_ID = :uuid" ;
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
     * This function will return the ApplicationID of the given Account
     * @param Integer $UUID The ID of the Account
     * @return string
     */
    public function getApplication($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Application from Account where Acc_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Application"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This will return the UserID of the given account
     * @param Integer $UUID The ID of the Account
     * @return string
     */
    public function getUserID($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select UserID from Account where Acc_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["UserID"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }

}
