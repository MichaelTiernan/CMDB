<?php
require_once 'Logger.php';

class IdentityGateway extends Logger{
    private static $table = 'identity';
    /**
     * This function will create a new Identity
     * @param string $FirstName
     * @param string $LastName
     * @param string $company
     * @param string $language
     * @param string $UserID
     * @param int $type
     * @param string $email
     * @param string $AdminName The name of the administrator that did the creation
     */
    public function create($FirstName,$LastName,$company, $language,$UserID,$type,$email,$AdminName) {
        $UserID = ($UserID!= NULL)?$UserID:NULL;
            $company = ($company != NULL)?$company:NULL;
            $Name = $FirstName.", ".$LastName;
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO Identity (Name,UserID,Type,Language,Company,E_Mail) values(:name, :userid, :type, :language, :company, :email)";
            $q = $pdo->prepare($sql);
            $q->bindParam(':name',$Name);
            $q->bindParam(':userid',$UserID);
            $q->bindParam(':language',$language);
            $q->bindParam(':company',$company);
            $q->bindParam(':type',$type);
            $q->bindParam(':email',$email);
            if ($q->execute()){
                $Value = "Identity width name: ".$FirstName." ".$LastName;
                $UUIDQ = "Select Iden_ID from Identity order by Iden_ID desc limit 1";
                $stmnt = $pdo->prepare($UUIDQ);
                $stmnt->execute();
                $row = $stmnt->fetch(PDO::FETCH_ASSOC);
                Logger::logCreate(self::$table, $row["Iden_ID"], $Value, $AdminName);
            }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::delete()
     */
    public function delete($UUID,$reason, $AdminName) {
        $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Identity set Active = 0, Deactivate_reason = :reason where Iden_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':reason',$reason);
            if ($q->execute()){
                $Value = "Identity with ".  $this->getFirstName($UUID)." ".  $this->getLastName($UUID);
                $this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
            }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::activate()
     */
    public function activate($UUID, $AdminName) {
        $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Update Identity set Active = 1, Deactivate_reason = NULL where Iden_id = :uuid";
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $Value = "Identity with ".  $this->getFirstName($UUID)." ".  $this->getLastName($UUID);
                $this->logActivation(self::$table, $UUID, $Value, $AdminName);
            }
        Logger::disconnect();
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectById()
     */
    public function selectById($id) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Name, UserID, E_Mail, Company, Language,it.Type_ID, it.Type, if(i.active=1,\"Active\",\"Inactive\") as Active, i.Deactivate_reason "
                . "from Identity i join identitytype it on i.type = it.type_id where Iden_id=:id";
        $q = $pdo->prepare($sql);
        $q->bindParam(':id',$id);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will update a given Identity
     * @param int $UUID The Unique ID of the Identity
     * @param string $FirstName 
     * @param string $LastName
     * @param string $UserID
     * @param int $type
     * @param string $email
     * @param string $AdminName The name of the administrator that did the update
     */
    public function update($UUID,$FirstName,$LastName,$UserID,$company, $language,$type,$email,$AdminName) {
        $OldFirstName = $this->getFirstName($UUID);
        $OldLastName = $this->getLastName($UUID);
        $OldCompany = $this->getCompany($UUID);
        $OldLanguage = $this->getLanguage($UUID);
        $OldType = $this->getType($UUID);
        $OldEmail = $this->getEmail($UUID);
        $OldUserID = $this->getUserID($UUID);
        //detect changes
        if (strcmp($OldFirstName, $FirstName) != 0){
            $this->logUpdate(self::$table, $UUID, "FirstName", $OldFirstName, $FirstName, $AdminName);
            $Name = $FirstName.", ".$LastName;
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set Name = :name where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':name',$Name);
            $q->execute();
            Logger::disconnect();
        }
        if (strcmp($OldLastName, $LastName) != 0){
            $this->logUpdate(self::$table, $UUID, "LastName", $OldLastName, $LastName, $AdminName);
            $Name = $FirstName.", ".$LastName;
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set Name = :name where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':name',$Name);
            $q->execute();
            Logger::disconnect();
        }
        if (strcmp($OldCompany, $company) != 0){
            $this->logUpdate(self::$table, $UUID, "Company", $OldCompany, $company, $AdminName);
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set Company = :company where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':company',$company);
            $q->execute();
            Logger::disconnect();
        }
        if (strcmp($OldLanguage, $language) != 0){
            $this->logUpdate(self::$table, $UUID, "Language", $OldLanguage, $language, $AdminName);
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set Language = :language where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':language',$company);
            $q->execute();
            Logger::disconnect();
        }
        if (strcmp($OldEmail, $email) != 0){
            $this->logUpdate(self::$table, $UUID, "EMail", $OldEmail, $email, $AdminName);
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set E_Mail = :email where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':email',$email);
            $q->execute();
            Logger::disconnect();
        }
        if (strcmp($OldUserID, $UserID) != 0){
            $this->logUpdate(self::$table, $UUID, "UserID", $OldUserID, $UserID, $AdminName);
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Update Identity set UserID = :userid where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':userid',$email);
            $q->execute();
            Logger::disconnect();
        }
        if ($OldType != $type){
            $OldIdentityType = $this->getIdentityType($UUID);
            $pdo = Logger::connect();
            $IdentityQ = "Select Type from IdentityType where Type_ID = :uuid";
            $Typeq = $pdo->prepare($IdentityQ);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $Typeq->bindParam(':uuid',$type);
            $Typeq->execute();
            $row = $Typeq->fetch(PDO::FETCH_ASSOC);
            $this->logUpdate(self::$table, $UUID, "Type", $OldIdentityType, $row["Type"], $AdminName);
            $sql =  "Update Identity set Type = :type where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':type',$type);
            $q->execute();
            Logger::disconnect();
        } 
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectAll()
     */
    public function selectAll($order) {
        if (empty($order)) {
            $order = "Name";
        }
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select Iden_Id, Name, UserID, E_Mail, Language, it.Type, if(i.active=1,\"Active\",\"Inactive\") as Active, i.Deactivate_reason "
                . "from Identity i join identitytype it on i.type = it.type_id order by ".$order;
        $q = $pdo->prepare($sql);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will check if there is an other Identity existing with the same user ID. 
     * @param string $UserID The user ID of the Identity
     * @return Boolean
     * @throws PDOException
     */
    public function UserIDChecker($UserID) {
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "Select UserID from Identity where UserID =:UserID";
            $q = $pdo->prepare($sql);
            $q->bindParam(':UserID',$UserID);
            $q->execute();
//            print_r($q->fetchAll(PDO::FETCH_ASSOC));
            if ($q->rowCount()>0){
                return TRUE;
            }  else {
                return FALSE;
            }
 //           return TRUE;
        }  catch (PDOException $e){
            throw $e;
        }
        Logger::disconnect();
    }
    /**
     * This will list all available Identities that are not assigned to an account
     * @return type
     */
    public function listAllIdentities() {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $SQL = "select i.Iden_ID, Name, UserID "
            . "from identity i "
            . "where i.Iden_ID not in "
            . "(select Identity from idenaccount ia where now() between ia.ValidFrom and ia.ValidEnd) "
            . "and I.Iden_ID != 1 "
            . "and I.Active = 1";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This will list all available accounts that are not assigned to an identity
     * @return array
     */
    public function listAllAccounts() {
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
    /**
     * This function will list all Accounts assigned to the given account
     * @param int $UUID The Unique ID of the Identity
     * @return Array
     */
    public function listAssignedAccount($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $SQL = "select a.`Acc_ID`,a.`UserID`,app.`Name` Application, ia.ValidFrom, ia.ValidEnd "
            ."from account a " 
            ."join application app on a.`Application` = app.`App_ID` "
            ."join idenaccount ia on ia.Account= a.Acc_ID "
            ."where ia.Identity = :uuid";
        $q = $pdo->prepare($SQL);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
    /**
     * This function will assign a Identity to an Account
     * @param int $UUID the unique ID of the Identity
     * @param int $account the unique ID of the Account
     * @param DateTime $From The from date
     * @param DateTime $Until The Until Date
     * @param String $AdminName The name of the person that did the Assignment
     */
    public function AssignAccount($UUID,$account,$From,$Until,$AdminName) {
        if (empty($Until)){
            $newUntilDate = "31/12/2037";
        }else{
            $newUntilDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$Until);
        }
        $newFromDate = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$From);
        //$FromDate = date('Y-M-D H:i:s',strtotime($From));
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Insert into idenaccount (Identity, Account, ValidFrom, ValidEnd) "
                . "values (:uuid, :account, :from, :until)" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        $q->bindParam(':account',$account);
        $q->bindParam(':from',$newFromDate);
        $q->bindParam(':until',$newUntilDate);
        if ($q->execute()){
            require_once 'AccountGateway.php';
            $Account = new AccountGateway();
            $AppID = $Account->getApplication($account);
            $IdenValue = "Identity with Name ".$this->getFirstName($UUID)." ".$this->getLastName($UUID);
            $AccountValue = "Account with UserID ".$Account->getUserID($account)." in Application ".$Account->getApplicationName($AppID);
            $this->logAssignIden2Account(self::$table, $UUID, $IdenValue, $AccountValue, $AdminName);
            $this->logAssignAccount2Iden("account", $account, $IdenValue, $AccountValue, $AdminName);
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
        $sql = "Select Iden_Id, Name, UserID, E_Mail, Language, it.Type, if(i.active=1,\"Active\",\"Inactive\") as Active, i.Deactivate_reason "
                . "from Identity i join identitytype it on i.type = it.type_id "
                . "where Name like :search or UserID like :search "
                . "or E_Mail like :search or Language like :search "
                . "or it.Type like :search";
        $q = $pdo->prepare($sql);
        $q->bindParam(':search',$searhterm);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }

    /**
     * This function will return the Type
     * @param int $UUID the unique ID of the Identity
     * @return string
     */
    public function getUserID($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select UserID from Identity where Iden_ID = :uuid" ;
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
    /**
     * This function will return the FirstName
     * @param Int $UUID the unique ID of the Identity
     * @return string
     */
    public function getFirstName($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Name from Identity where Iden_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            $Name = explode(", ", $row["Name"]);
            $FristName = $Name[0];
            return $FristName;
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the LastName
     * @param int $UUID the unique ID of the Identity
     * @return string
     */
    public  function getLastName($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Name from Identity where Iden_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            $Name = explode(", ", $row["Name"]);
            $LastName = $Name[1];
            return $LastName;
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Company
     * @param int $UUID the unique ID of the Identity
     * @return string
     */
    private function getCompany($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Company from Identity where Iden_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Company"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Language
     * @param int $UUID the unique ID of the Identity
     * @return string
     */
    private function getLanguage($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select Language from Identity where Iden_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["Language"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
    /**
     * This function will return the Type
     * @param int $UUID The unique ID of the Identity
     * @return string
     */
    private function getType($UUID){
        try{
            $pdo = Logger::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "Select Type from Identity where Iden_ID = :uuid" ;
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            if ($q->execute()){
                $row = $q->fetch(PDO::FETCH_ASSOC);
                return $row["Type"];
            }  else {
                return "";
            }
            Logger::disconnect();
        }  catch (PDOException $ex){
            throw $ex;
        }
    }
    /**
     * This function will return the Identity Type
     * @param int $UUID The Unique iD of the Identity Type
     * @return string
     */
    private function getIdentityType($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select it.Type from Identity i join identitytype it where Iden_ID = :uuid" ;
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
     * This function will return the Type
     * @param int $UUID
     * @return string
     */
    private function getEmail($UUID){
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql =  "Select E_Mail from Identity where Iden_ID = :uuid" ;
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["E_Mail"];
        }  else {
            return "";
        }
        Logger::disconnect();
    }
}
