<?php
require_once 'Logger.php';
class AdminGateway extends Logger {
	/**
	 * This variable will keep the table for the logging
	 * @var string
	 */
	private static $table = 'admin';
	/**
	 * {@inheritDoc}
	 */
	public function activate($UUID, $AdminName) {
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Update Admin set Deactivate_reason = NULL, Active = 1 where Admin_ID = :uuid";
		$q = $pdo->prepare($sql);
		$q->bindParam(':uuid',$UUID);
		if ($q->execute()){
			$AccountID =$this->getAccountID($UUID);
			$Value = "The Administrator with account ".$this->getAccount($AccountID)." and level ".$this->getLevel($UUID);
			$this->logActivation(self::$table, $UUID, $Value, $AdminName);
		}
	}
	/**
	 * {@inheritDoc}
	 */
	public function delete($UUID, $reason, $AdminName) {
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Update Admin set Deactivate_reason = :reason, Active = 0 where Admin_ID = :uuid";
		$q = $pdo->prepare($sql);
		$q->bindParam(':uuid',$UUID);
		$q->bindParam(':reason',$reason);
		if ($q->execute()){
			$AccountID =$this->getAccountID($UUID);
			$Value = "The Administrator with account ".$this->getAccount($AccountID)." and level ".$this->getLevel($UUID);
			$this->logDelete(self::$table, $UUID, $Value, $reason, $AdminName);
		}
	}
	/**
	 * {@inheritDoc}
	 */
	public function selectAll($order) {
		if (empty($order)) {
			$order = "Account";
		}
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Select Admin_id, a.UserID Account, Level, if(admin.active=1,\"Active\",\"Inactive\") as Active from admin join Account a on Account = a.Acc_ID  order by ".$order;
		$q = $pdo->prepare($sql);
		if ($q->execute()){
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		Logger::disconnect();
	}
	/**
	 * {@inheritDoc}
	 */
	public function selectBySearch($search){
		$searhterm = "%$search%";
		$pdo = Logger::connect();
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Select Admin_id, a.UserID Account,a.Acc_ID, Level, if(admin.active=1,\"Active\",\"Inactive\") as Active ".
				"from admin join Account a on Account = a.Acc_ID where UserID like :search or Level like :search";
		$q = $pdo->prepare($sql);
		$q->bindParam(':search',$searhterm);
		if ($q->execute()){
			return $q->fetchAll(PDO::FETCH_ASSOC);
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
		$sql = "Select Admin_id, a.UserID Account,a.Acc_ID, Level, if(admin.active=1,\"Active\",\"Inactive\") as Active ".
		 "from admin join Account a on Account = a.Acc_ID where Admin_id = :uuid";
		$q = $pdo->prepare($sql);
		$q->bindParam(':uuid',$id);
		if ($q->execute()){
			return $q->fetchAll(PDO::FETCH_ASSOC);
		}
		Logger::disconnect();
	}
	/**
	 * This function will create a new Administrator
	 * @param int $account
	 * @param int $level
	 * @param string $AdminName The name of the administrator that did the creation
	 */
	public function create($account, $level, $AdminName){
		$pwd = md5("cmdb");
		$LogDate = date("y-m-d h:i:s");
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "insert into Admin (Account,Level,PassWord,DateSet) values (:Account,:Level,:PWD,:Date)";
		$q = $pdo->prepare($sql);
		$q->bindParam(':Account',$account);
		$q->bindParam(':Level',$level);
		$q->bindParam(':PWD',$pwd);
		$q->bindParam(':Date',$LogDate);
		if ($q->execute()){
			$Value = "Admin width UserID: ".$this->getAccount($account)." and level: ".$level;
            $UUIDQ = "Select Admin_id from Admin order by Admin_ID desc limit 1";
            $stmnt = $pdo->prepare($UUIDQ);
            $stmnt->execute();
            $row = $stmnt->fetch(PDO::FETCH_ASSOC);
            Logger::logCreate(self::$table, $row["Admin_id"], $Value, $AdminName);
		}
		Logger::disconnect();
	}
	/**
	 * This function will update a given administrator
	 * @param int $UUID
	 * @param int $account
	 * @param int $level
	 * @param string $AdminName
	 */
	public function update($UUID,$account, $level, $AdminName){
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$OldAccount = $this->getAccountID($UUID);
		$OldLevel = $this->getLevel($UUID);
		$changed = FALSE;
		if ($OldAccount != $account){
			$changed = TRUE;
			$this->logUpdate(self::$table, $UUID, "Account", $this->getAccount($OldAccount), $this->getAccount($account), $AdminName);
		}
		if ($OldLevel != $level){
			$changed = TRUE;
			$this->logUpdate(self::$table, $UUID, "Level", $OldLevel, $level, $AdminName);
		}
		if ($changed){
			$sql = "Update Admin set Account = :account, Level = :level where Admin_ID = :uuid";
			$q = $pdo->prepare($sql);
			$q->bindParam(':uuid',$UUID);
			$q->bindParam(':account',$account);
			$q->bindParam(':level', $level);
			$q->execute();
		}
	}
	/**
	 * This function will list all Accounts for the application CMDB
	 */
	public function getAllAccount(){
		$pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $SQL = "select a.`Acc_ID`,a.`UserID`,app.`Name` Application "
            ."from account a " 
            ."join application app on a.`Application` = app.`App_ID` "
            ."where app.`Name` = 'CMDB' "
            ."and a.`Active` = 1";
        $q = $pdo->prepare($SQL);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
	}
	/**
	 * This function will return all Levels
	 */
	public function getAllLevels(){
		require_once 'AccessGateway.php';
		$Sec = new AccessGateway();
		return $Sec->listAllLevels();
	}
	/**
	 * This function will return the Level of an Administrator
	 * @param int $UUID
	 * @return int
	 */
	public function getLevel($UUID){
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Select Level from Admin where Admin_id = :uuid";
		$q = $pdo->prepare($sql);
		$q->bindParam(':uuid',$UUID);
		if ($q->execute()){
			$row = $q->fetch(PDO::FETCH_ASSOC);
			return $row["Level"];
		}else{
			return 0;
		}
	}
	/**
	 * This function will return the Level of an Administrator
	 * @param int $UUID
	 * @return int
	 */
	public function getAccountID($UUID){
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "Select Account from Admin where Admin_id = :uuid";
		$q = $pdo->prepare($sql);
		$q->bindParam(':uuid',$UUID);
		if ($q->execute()){
			$row = $q->fetch(PDO::FETCH_ASSOC);
			return $row["Account"];
		}else{
			return 0;
		}
	}
	/**
	 * 
	 * @param int $Level
	 * @param int $Admin
	 * @param number $UUID
	 */
	public function alreadyExist($Level,$Admin,$UUID = 0){
		$pdo = Logger::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if ($UUID == 0){
			$sql =  "Select * from Admin where Account = :account" ;
			$q = $pdo->prepare($sql);
			$q->bindParam(':account',$Admin);
			$q->execute();
			if ($q->rowCount()>0){
				return TRUE;
			}  else {
				return FALSE;
			}
		}else{
			$sql =  "Select * from Admin where Account = :account and Level= :level" ;
			$q = $pdo->prepare($sql);
			$q->bindParam(':account',$Admin);
			$q->bindParam(':level',$Level);
			$q->execute();
			if ($q->rowCount()>0){
				return TRUE;
			}  else {
				return FALSE;
			}
		}
	}
	/**
	 * This function will return the UserID of a given Account
	 * @param int $AccountID The unique of an account
	 */
	private function getAccount($AccountID){
		require_once 'AccountGateway.php';
		$Acc = new AccountGateway();
		return $Acc->getUserID($AccountID);
	}
}