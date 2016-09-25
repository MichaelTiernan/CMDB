<?php
require_once 'Database.php';

abstract class Logger extends Database{
    private $LogText = '';
    
    abstract function delete($UUID, $reason,$AdminName);
    abstract function activate($UUID,$AdminName);
    abstract function selectAll($order);
    abstract function selectById($id);
    /**
     * This function will rerutn anny matchin row by the given search term
     * @param string $search Anny term
     * @return Array
     */
    abstract function selectBySearch($search);
    /**
     * This function will check if the given assetTag is unique
     * @param string $AssetTag
     * @return boolean
     */
    public function isAssetTagUnique($AssetTag) {
        $result = true;
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "Select serialnumber "
                . "from asset at "
                . "where AssetTag = :assettag";
        $q = $pdo->prepare($sql);
        $q->bindParam(':assettag',$AssetTag);
        $q->execute();
        if ($q->rowCount()>0){
            $result = FALSE;
        }
        Database::disconnect();
        return $result;
    }
    /**
     * 
     * @param type $Table
     * @param type $UUID
     * @param type $Value
     * @param type $AdminName
     */
    protected function logCreate($Table,$UUID,$Value,$AdminName){
        $this->LogText = "The ".$Value." is created by ".$AdminName." in table ".$Table;
        $this->doLog($Table, $UUID);
    }
    /**
     * this function will log the update of a field in a table
     * @param type $Table
     * @param type $UUID
     * @param type $field
     * @param type $oldValue
     * @param type $NewValue
     * @param type $AdminName
     */
    protected function logUpdate($Table,$UUID,$field,$oldValue,$NewValue,$AdminName){
        if (empty($oldValue)){
            $oldValue = "Empty";
        }
        if (empty($NewValue)){
            $NewValue = "Empty";
        }
        $this->LogText = "The ".$field." in table ".$Table." has been changed from ".$oldValue." to 
            ".$NewValue." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * 
     * @param type $Table
     * @param type $UUID
     * @param type $Value
     * @param type $reason
     * @param type $AdminName
     */
    protected function logDelete($Table,$UUID,$Value,$reason,$AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is deleted du to ".$reason." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * 
     * @param type $Table
     * @param type $UUID
     * @param type $Value
     * @param type $AdminName
     */
    protected function logActivation($Table,$UUID,$Value,$AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is activated by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * 
     * @param String $Table
     * @param Integer $UUID
     * @param String $Value
     * @param String $AccountInfo
     * @param String $AdminName
     */
    protected function logAssignIden2Account($Table,$UUID,$Value,$AccountInfo,$AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is assigned to ".$AccountInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }
    /**
     * 
     * @param type $Table
     * @param type $UUID
     * @param type $Value
     * @param type $IdenInfo
     * @param type $AdminName
     */
    protected function logAssignAccount2Iden($Table,$UUID,$Value, $IdenInfo, $AdminName){
        $this->LogText = "The ".$Value." in table ".$Table." is assigned to ".$IdenInfo." by ".$AdminName;
        $this->doLog($Table, $UUID);
    }

    /**
     * 
     * @param type $Table
     * @param type $UUID
     * @throws PDOException
     */
    private function doLog($Table,$UUID){
        try{
            $pdo = Database::connect();
            $LogDate = date("d-m-y h:i:s");
            switch ($Table){
                case "identity":
                    $sql = "INSERT INTO log (Identity,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "identitytype":
                    $sql = "INSERT INTO log (IdentityType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "account":
                    $sql = "INSERT INTO log (Account,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "accounttype":
                    $sql = "INSERT INTO log (AccountType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "role":
                    $sql = "INSERT INTO log (Role,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "roletype":
                    $sql = "INSERT INTO log (RoleType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "assettype":
                    $sql = "INSERT INTO log (AssetType,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "devices":
                    $sql = "INSERT INTO log (AssetTag,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "menu":
                    $sql = "INSERT INTO log (menu,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "permissions":
                    $sql = "INSERT INTO log (permissions,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "role_perm":
                    $sql = "INSERT INTO log (role_perm_id,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "application":
                    $sql = "INSERT INTO log (Application,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "token":
                    $sql = "INSERT INTO log (AssetTag,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                case "kensington":
                    $sql = "INSERT INTO log (Kensington,Log_Text,Log_Date) values(:uuid, :log_text, :log_date)";
                    break;
                default :
                    throw new Exception('Table not Know');
            }
            $q = $pdo->prepare($sql);
            $q->bindParam(':uuid',$UUID);
            $q->bindParam(':log_text',$this->LogText);
            $q->bindParam(':log_date',$LogDate);
            $q->execute();
            Database::disconnect();
        } catch (PDOException $ex) {
            throw $ex;
        } catch (Exception $e){
            throw $e;
        }
    }
}
