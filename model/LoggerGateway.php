<?php
require_once 'Logger.php';
class LoggerGateway extends Logger {
    /**
     * {@inheritDoc}
     * @see Logger::activate()
     */
    public function activate($UUID, $AdminName) {
        echo 'UUID: '.$UUID. " AdminName ".$AdminName;
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::delete()
	 */
    public function delete($UUID, $reason, $AdminName) {
         echo 'UUID: '.$UUID. " AdminName ".$AdminName." reason ".$reason;
    }
    /**
     * This function will return the log lines for a given table.
     * @param string $table
     * @param mixed $UUID
     * @return array
     */
    public function getLog($table, $UUID) {
        $pdo = Logger::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        switch ($table){
            case "identity":
                $sql = "Select Log_Text, Log_Date from Log where Identity=:uuid order by Log_ID Desc";
                break;
            case "identitytype":
                $sql = "Select Log_Text, Log_Date from Log where IdentityType=:uuid order by Log_ID Desc";
                break;
            case "account":
                $sql = "Select Log_Text, Log_Date from Log where Account=:uuid order by Log_ID Desc";
                break;
            case "accounttype":
                $sql = "Select Log_Text, Log_Date from Log where AccountType=:uuid order by Log_ID Desc";
                break;
            case "role":
                $sql = "Select Log_Text, Log_Date from Log where Role=:uuid order by Log_ID Desc";
                break;
            case "roletype":
                $sql = "Select Log_Text, Log_Date from Log where RoleType=:uuid order by Log_ID Desc";
                break;
            case "assettype":
                $sql = "Select Log_Text, Log_Date from Log where AssetType=:uuid order by Log_ID Desc";
                break;
            case "devices":
                $sql = "Select Log_Text, Log_Date from Log where AssetTag=:uuid order by Log_ID Desc";
                break;
            case "permissions":
                $sql = "Select Log_Text, Log_Date from Log where permissions=:uuid order by Log_ID Desc";
                break;
            case "menu":
                $sql = "Select Log_Text, Log_Date from Log where menu=:uuid order by Log_ID Desc";
                break;
            case "role_perm":
                $sql = "Select Log_Text, Log_Date from Log where role_perm_id=:uuid order by Log_ID Desc";
                break;
            case "application":
                $sql = "Select Log_Text, Log_Date from Log where Application=:uuid order by Log_ID Desc";
                break;
            case "token":
                $sql = "Select Log_Text, Log_Date from Log where AssetTag=:uuid order by Log_ID Desc";
                break;
            case "kensington":
                $sql = "Select Log_Text, Log_Date from Log where Kensington=:uuid order by Log_ID Desc";
                break;
            case "admin":
                	$sql = "Select Log_Text, Log_Date from Log where Admin=:uuid order by Log_ID Desc";
                	break;
            default :
                throw new Exception("Table ".$table." not available in Logger");
        }
        $q = $pdo->prepare($sql);
        $q->bindParam(':uuid',$UUID);
        if ($q->execute()){
            return $q->fetchAll(PDO::FETCH_ASSOC); 
        }
        Logger::disconnect();
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::selectAll()
	 */
    public function selectAll($order) {
        echo "The order is ".$order;
    }
	/**
	 * {@inheritDoc}
	 * @see Logger::selectById()
	 */
    public function selectById($id) {
        echo "The Id is ".$id;
    }
    /**
     * {@inheritDoc}
     * @see Logger::selectBySearch()
     */
    public function selectBySearch($search){
        echo "The Search is ".$search;
    }
}
