<?php
require_once '../utils/PHPExcel/Classes/PHPExcel.php';
$exampleFile = "ODS_Beschrijving_AccountManagement_AFC_R3.xlsx";
$pathInfo = pathinfo($exampleFile);
$type = $pathInfo['extension'] == 'xlsx' ? 'Excel2007' : 'Excel5';

$objReader = PHPExcel_IOFactory::createReader($type);
$objPHPExcel = $objReader->load($exampleFile);
$objPHPExcel->setActiveSheetIndex(1);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
$tabelOds = "";
$prevTabelOds ="";
$tableMob = "";
$prevTableMob ="";
$insersql = "INSERT INTO ";
$selectsql ="SELECT ";
$createsql ="CREATE TABLE ";
$FORSql = "FOR r_upd IN ( SELECT y.*, y.ora_rowscn scn "
        . "FROM ";
$upateSql = "";
$lowerbound = 7;
$upperbound = 13;
function printInser($insersql){
    print "<hr>Insert statement: <br>";
    PRINT  $insersql.")<br>";  
}
function printSelect($selectsql){
    print $selectsql."<BR>";
}
function printCreate($createsql){
	print "Create statement: <br>";
	print $createsql."<BR>";
}
function printUpdate($upateSql){
    print $upateSql." ods_gewijzigd=systimestamp, ods_scn=r_upd.scn WHERE ID = r_upd.ID; END LOOP; <br>";
}
function printFor($forSQL){
    print "Update statment: <br>";
    print $forSQL."<br>";
}
function getLowerBounds($tableName){
	switch ($tableName){
		case "ACCOUNTS":
			return 7;
			break;
		case "B2B_ACCOUNTS":
			return 19;
			break;
		case "B2B_CONTRACTEN": 
			return 29;
			break;
		case "B2B_CONTRACTONDERDELEN":
			return 44;
			break;
		case "CONTRACTEN":
			return 73;
			break;
		case "CONTRACT_ARTIKELEN":
			return 121;
			break;
		case "CONTRACT_ARTIKEL_ATTRIBUTEN":
			return 130;
			break;
		case "EVENTS":
			return 145;
			break;
		case "GEOGRAFISCHE_BEPERKINGEN":
			return 157;
			break;
		case "KANDIDAAT_BEGUNSTIGDEN":
			return 166;
			break;
		case "LEEFTIJD_BEPERKINGEN":
			return 176;
			break;
		case "PERSOONPROFIEL_BEPERKINGEN":
			return 185;
			break;
		case "STOPZETTING_RPC_REDENEN":
			return 192;
			break;
		case "TARIEF_BEPERKINGEN":
			return 198;
			break;
	}
}
function getUpperbound($tablename){
	switch ($tablename){
		case "ACCOUNTS":
			return 14;
			break;
		case "B2B_ACCOUNTS":
			return 24;
			break;
		case "B2B_CONTRACTEN":
			return 39;
			break;
		case "B2B_CONTRACTONDERDELEN":
			return 66;
			break;
		case "CONTRACTEN":
			return 116;
			break;
		case "CONTRACT_ARTIKELEN":
			return 125;
			break;
		case "CONTRACT_ARTIKEL_ATTRIBUTEN":
			return 139;
			break;
		case "EVENTS":
			return 151;
			break;
		case "GEOGRAFISCHE_BEPERKINGEN":
			return 159;
			break;
		case "KANDIDAAT_BEGUNSTIGDEN":
			return 170;
			break;
		case "LEEFTIJD_BEPERKINGEN":
			return 179;
			break;
		case "PERSOONPROFIEL_BEPERKINGEN":
			return 187;
			break;
		case "STOPZETTING_RPC_REDENEN":
			return 193;
			break;
		case "TARIEF_BEPERKINGEN":
			return 200;
			break;
	}
}
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if(1 == $row->getRowIndex ()) continue;//skip row
    if(2 == $row->getRowIndex ()) continue;//skip row
    if(3 == $row->getRowIndex ()) continue;//skip row
    if(4 == $row->getRowIndex ()) continue;//skip row
    $rowIndex = $row->getRowIndex ();
    foreach ($cellIterator as $cell) {
        if('A' == $cell->getColumn()){
        	//echo 'Data in Cell A:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
        	if (!empty($cell->getCalculatedValue())){
//                secho 'Data in Cell A:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
                if (strcmp($tabelOds, $cell->getCalculatedValue()) != 0){
                    $prevTabelOds = $tabelOds;
                    $tabelOds =  $cell->getCalculatedValue();
                    $insersql .= "ODS_AANGEMAAKT, ODS_GEWIJZIGD, ODS_SCN";
                    
                }
            }
        }
        if('B' == $cell->getColumn()){
            //echo 'Data in Cell B:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
            if (!empty($cell->getCalculatedValue())){
                if (!empty($cell->getCalculatedValue())){
                    if (strcmp($tableMob, $cell->getCalculatedValue()) != 0){
                        $prevTableMob = $tableMob;
                        $tableMob = $cell->getCalculatedValue();
                        $selectsql .= " SYSTIMESTAMP, NULL, ORA_ROWSCN FROM "
                                .$prevTableMob."@ACCMGMT o WHERE NOT EXIST (SELECT ID FROM "
                                .$prevTabelOds." I where I.ID =O.ID);<br>";
                        $FORSql .= $prevTableMob."@ACCMGMT y JOIN ".$prevTabelOds." z ON z.ID = y.ID "
                                . "WHERE z.ods_scn &#60 y.ora_rowscn) LOOP";
                        printInser($insersql);
                        $insersql = "INSERT INTO ";
                        $insersql .= "\"".$tabelOds."\" ( ";
    //                    echo 'insert statment now: '.$insersql."<br>";
//                        $upateSql .= "\"".$tabelOds."\" SET ";
                        printSelect($selectsql);
                        $selectsql ="SELECT ";
                        printFor($FORSql);
                        printUpdate($upateSql);
                        $upateSql = "UPDATE \"".$tabelOds."\" SET ";
                        $FORSql ="FOR r_upd IN ( SELECT y.*, y.ora_rowscn scn "
                                . "FROM ";
                        $createsql .= "ODS_AANGEMAAKT TIMESTAMP, "
    							. "ODS_GEWIJZIGD TIMESTAMP, "
    							."ODS_SCN NUMBER";
                        $createsql .= ");";
                        //echo 'Create statement now: '.$createsql."<br>";
                        printCreate($createsql);
                        $createsql ="CREATE TABLE ".$tabelOds."( ";
                        
                        //echo 'Create statement now: '.$createsql."<br>";
                    }
                }
            }
        }
        if('C' == $cell->getColumn()){
            //echo 'Data in Cell F:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
            if (!empty($cell->getCalculatedValue())){
                $insersql .= $cell->getCalculatedValue().", ";
                //echo 'insert statment now: '.$insersql."<br>";
                $upateSql .= $cell->getCalculatedValue()."=r_upd.";
                $createsql .= $cell->getCalculatedValue()." ";
                //echo 'Create statement now: '.$createsql."<br>";
            }
        }
        if('D' == $cell->getColumn()){
            //echo 'Data in Cell F:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
            if (!empty($cell->getCalculatedValue())){
                $selectsql .= $cell->getCalculatedValue().", ";
               // echo 'select statment now: '.$selectsql."<br>";
                 $upateSql .= $cell->getCalculatedValue().", ";
            }
        }
        if('E' == $cell->getColumn()){
        	//echo 'Data in Cell E:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
        	if (!empty($cell->getCalculatedValue())){
        		$createsql .= $cell->getCalculatedValue()." ";
        		//echo 'Create statement now: '.$createsql."<br>";
        	}
        }
        if('F' == $cell->getColumn()){
        	if ($row->getRowIndex() >= getLowerBounds($tabelOds) and $row->getRowIndex() <= getUpperbound($tabelOds)){
        		//echo 'Data in Cell F:'.$row->getRowIndex()." ".$cell->getCalculatedValue()."<BR>";
        		$createsql .= $cell->getCalculatedValue().", ";
        		//echo 'Create statement now: '.$createsql."<br>";
        	}
        }
    }
}
