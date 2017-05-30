<?php
require_once 'Database.php';
class configuration extends Database
{
    /**
     * This function will return the date format used in the application
     * @return string
     */
    public function getDataFormat(){
        $Code ="General";
        $SubCode = "DateFormat";
        $pdo = Database::connect();
        $sql = "select TEXT from configuration where Code = :code and SUB_CODE = :subcode";
        $q = $pdo->prepare($sql);
        $q->bindParam(':code',$Code);
        $q->bindParam(':subcode',$SubCode);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["TEXT"];
        }  else {
            return "d-m-Y";
        }
    }
    /**
     * This function will return the dae format used in the application
     * @return string
     */
    public function getLogDataFormat(){
        $Code ="General";
        $SubCode = "LogDateFormat";
        $pdo = Database::connect();
        $sql = "select TEXT from configuration where Code = :code and SUB_CODE = :subcode";
        $q = $pdo->prepare($sql);
        $q->bindParam(':code',$Code);
        $q->bindParam(':subcode',$SubCode);
        if ($q->execute()){
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return $row["TEXT"];
        }  else {
            return "d-m-Y h:i:s";
        }
    }
}