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
		$sql = "Select a.UserID Account, Level, if(admin.active=1,\"Active\",\"Inactive\") as Active from admin join Account a on Account = a.Acc_ID  order by ".$order;
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
	}
	/**
	 * This function will create a new Admin
	 * @param unknown $account
	 * @param unknown $level
	 * @param unknown $AdminName
	 */
	public function create($account, $level, $AdminName){
		
	}
}