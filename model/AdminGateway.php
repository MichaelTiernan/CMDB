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
	 * @see Logger::activate()
	 */
	public function activate($UUID, $AdminName) {
		
	}
	/**
	 * {@inheritDoc}
	 * @see Logger::delete()
	 */
	public function delete($UUID, $reason, $AdminName) {
		
	}
	/**
	 * {@inheritDoc}
	 * @see Logger::selectAll()
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
	 * @see Logger::selectBySearch()
	 */
	public function selectBySearch($search){
		$searhterm = "%$search%";
		$pdo = Logger::connect();
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
	 * 
	 * @param int $UUID
	 * @param int $account
	 * @param int $level
	 * @param string $AdminName
	 */
	public function update($UUID,$account, $level, $AdminName){
		
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
		$SQL = "Select Level from Admin where Admin_id = :uuid";
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
		$SQL = "Select Account from Admin where Admin_id = :uuid";
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
		if ($UUD = 0){
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