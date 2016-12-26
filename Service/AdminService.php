<?php
require_once 'Service.php';
require_once 'model/AdminGateway.php';
class AdminService extends Service {
	private $adminGateway;
	
	public function __construct() {
		$this->adminGateway = new AdminGateway();
	}
	/**
	 * {@inheritDoc}
	 * @see Service::activate()
	 */
	public function activate($id, $AdminName) {
		$this->adminGateway->activate($id, $AdminName);
	}
	/**
	 * {@inheritDoc}
	 * @see Service::delete()
	 */
	public function delete($id, $reason, $AdminName) {
		try{
			$this->validateDeleteParams($reason);
			$this->adminGateway->delete($id, $reason, $AdminName);
		} catch (ValidationException $ex) {
			throw $ex;
		} catch (PDOException $e){
			throw $e;
		}
	}
	/**
	 * {@inheritDoc}
	 * @see Service::getAll()
	 */
	public function getAll($order) {
		try{
			$rows = $this->adminGateway->selectAll($order);
			return $rows;
		}  catch (PDOException $e){
			print $e;
		}
	}
	/**
	 * {@inheritDoc}
	 * @see Service::getByID()
	 */
	public function getByID($id) {
		return $this->adminGateway->selectById($id);
	}
	/**
	 * This function will create a new Account
	 * @param string $UserID The UserID of the Account
	 * @param int $Type The ID of the AccountType
	 * @param int $Application The ID of the Application
	 * @param string $AdminName The name of the Admin
	 * @throws ValidationException
	 * @throws PDOException
	 */
	public function create($UserID,$Type,$Application,$AdminName){
		try {
			$this->validateAccountParams($UserID, $Type, $Application);
			$this->adminGateway->create($UserID, $Type, $Application, $AdminName);
		} catch (ValidationException $exc) {
			throw $exc;
		} catch (PDOException $e){
			throw $e;
		}
	}
	/**
	 * This function will update a given Account
	 * @param int $UUID The unique ID of the Account
	 * @param string $UserID The UserID of the Account
	 * @param int $Type The ID of the AccountType
	 * @param int $Application The ID of the Application
	 * @param string $AdminName The name of the Admin
	 * @throws ValidationException
	 * @throws PDOException
	 */
	public function update($UUID,$UserID,$Type,$Application,$AdminName) {
		try {
			$this->validateAccountParams($UserID, $Type, $Application, $UUID);
			$this->adminGateway->update($UUID,$UserID, $Type, $Application, $AdminName);
		} catch (ValidationException $exc) {
			throw $exc;
		} catch (PDOException $e){
			throw $e;
		}
	}
	/**
	 * {@inheritDoc}
	 * @see Service::search()
	 */
	public function search($search) {
		return $this->accountGateway->selectBySearch($search);
	}
}