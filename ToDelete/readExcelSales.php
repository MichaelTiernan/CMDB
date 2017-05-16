<?php
require_once '../utils/PHPExcel/Classes/PHPExcel.php';
$exampleFile = "ODS_Beschrijving_SalesManagement_AFC_R3.xlsx";
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
$FORSql = "FOR r_upd IN ( SELECT y.*, y.ora_rowscn scn "
        . "FROM ";
$upateSql = "";
$createsql ="CREATE TABLE ";
$lowerbound = 7;
$upperbound = 13;
function printInser($insersql){
    print "<hr>Inser statment: <br>";
    PRINT  $insersql.")<br>";  
}
function printSelect($selectsql){
    print $selectsql."<BR>";
}

function printUpdate($upateSql){
    print $upateSql." ods_gewijzigd=systimestamp, ods_scn=r_upd.scn WHERE ID = r_upd.ID; END LOOP; <br>";
}
function printFor($forSQL){
    print "Update statment: <br>";
    print $forSQL."<br>";
}
function printCreate($createsql){
	print "Create statement: <br>";
	print $createsql."<BR>";
}
function getLowerBounds($tableName){
	switch ($tableName){
		case "AANVRAGEN":
			return 8;
			break;
		case "BETALINGSVOORSTELLEN":
			return 28;
			break;
		case "BETALINGSVOORSTEL_BATCHES":
			return 41;
			break;
		case "CODE_NAMEN":
			return 57;
			break;
		case "OFFERTELIJNEN":
			return 67;
			break;
		case "OFFERTELIJN_ADRESSEN":
			return 96;
			break;
		case "OFFERTELIJN_ARTIKELEN":
			return 110;
			break;
		case "OFFERTELIJN_PERSONEN":
			return 124;
			break;
		case "OFFERTELIJN_PERSOONPROFIELEN":
			return 145;
			break;
		case "OFFERTES":
			return 154;
			break;
		case "VERKOOPORDERLIJNEN":
			return 191;
			break;
		case "VERKOOPORDERS":
			return 208;
			break;
	}
}
function getUpperbound($tablename){
switch ($tablename){
		case "AANVRAGEN":
			return 21;
			break;
		case "BETALINGSVOORSTELLEN":
			return 34;
			break;
		case "BETALINGSVOORSTEL_BATCHES":
			return 51;
			break;
		case "CODE_NAMEN":
			return 60;
			break;
		case "OFFERTELIJNEN":
			return 88;
			break;
		case "OFFERTELIJN_ADRESSEN":
			return 103;
			break;
		case "OFFERTELIJN_ARTIKELEN":
			return 115;
			break;
		case "OFFERTELIJN_PERSONEN":
			return 137;
			break;
		case "OFFERTELIJN_PERSOONPROFIELEN":
			return 147;
			break;
		case "OFFERTES":
			return 182;
			break;
		case "VERKOOPORDERLIJNEN":
			return 199;
			break;
		case "VERKOOPORDERS":
			return 222;
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
                                .$prevTableMob."@SALES o WHERE NOT EXIST (SELECT ID FROM "
                                .$prevTabelOds." I where I.ID =O.ID);<br>";
                        $FORSql .= $prevTableMob."@SALES y JOIN ".$prevTabelOds." z ON z.ID = y.ID "
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
