<?php
require_once '../utils/PHPExcel/Classes/PHPExcel.php';
$exampleFile = "ODS_Beschrijving_PartyMasterDataManagement_AFC_R3.xlsx";
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
		case "ADRESSEN":
			return 9;
			break;
		case "AUDIT_EIGENSCHAPPEN":
			return 33;
			break;
		case "AUDIT_ENTITEITEN":
			return 45;
			break;
		case "AUDIT_EVENTS":
			return 59;
			break;
		case "B2B_PARTNERS":
			return 71;
			break;
		case "BRONNEN":
			return 87;
			break;
		case "CODES":
			return 99;
			break;
		case "CONTACT_INFOS":
			return 110;
			break;
		case "EXPLOITANTEN":
			return 128;
			break;
		case "GEMEENTES":
			return 145;
			break;
		case "GEZINNEN":
			return 166;
			break;
		case "GEZINSLEDEN":
			return 180;
			break;
		case "KAARTHOUDERNUMMERS":
			return 198;
			break;
		case "KLANTEN":
			return 216;
			break;
		case "LANDEN":
			return 235;
			break;
		case "MEDEWERKERS":
			return 252;
			break;
		case "ORGANISATIE_ENTITEITEN":
			return 273;
			break;
		case "ORGANISATORISCHE_EENHEDEN":
			return 281;
			break;
		case "PARTIJEN":
			return 297;
			break;
		case "PERSOONPROFIELEN":
			return 343;
			break;
		case "PERSOON_FOTOS":
			return 362;
			break;
		case "TOEGEKENDE_PERSOONPROFIELEN":
			return 378;
			break;
		case "WERKLOCATIES":
			return 397;
			break;
	}
}
function getUpperbound($tablename){
	switch ($tablename){
		case "ADRESSEN":
			return 25;
			break;
		case "AUDIT_EIGENSCHAPPEN":
			return 37;
			break;
		case "AUDIT_ENTITEITEN":
			return 51;
			break;
		case "AUDIT_EVENTS":
			return 64;
			break;
		case "B2B_PARTNERS":
			return 78;
			break;
		case "BRONNEN":
			return 91;
			break;
		case "CODES":
			return 103;
			break;
		case "CONTACT_INFOS":
			return 120;
			break;
		case "EXPLOITANTEN":
			return 137;
			break;
		case "GEMEENTES":
			return 158;
			break;
		case "GEZINNEN":
			return 171;
			break;
		case "GEZINSLEDEN":
			return 191;
			break;
		case "KAARTHOUDERNUMMERS":
			return 208;
			break;
		case "KLANTEN":
			return 226;
			break;
		case "LANDEN":
			return 245;
			break;
		case "MEDEWERKERS":
			return 266;
			break;
		case "ORGANISATIE_ENTITEITEN":
			return 274;
			break;
		case "ORGANISATORISCHE_EENHEDEN":
			return 290;
			break;
		case "PARTIJEN":
			return 336;
			break;
		case "PERSOONPROFIELEN":
			return 355;
			break;
		case "PERSOON_FOTOS":
			return 371;
			break;
		case "TOEGEKENDE_PERSOONPROFIELEN":
			return 390;
			break;
		case "WERKLOCATIES":
			return 405;
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
                                .$prevTableMob."@PMD o WHERE NOT EXIST (SELECT ID FROM "
                                .$prevTabelOds." I where I.ID =O.ID);<br>";
                        $FORSql .= $prevTableMob."@PMD y JOIN ".$prevTabelOds." z ON z.ID = y.ID "
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
