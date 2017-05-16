<?php
require_once '../utils/PHPExcel/Classes/PHPExcel.php';
$exampleFile = "ODS_Beschrijving_RevenueManagement_AFC_R3.xlsx";
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
		case "AUDIT_EIGENSCHAPPEN":
			return 9;
			break;
		case "AUDIT_ENTITEITEN":
			return 22;
			break;
		case "AUDIT_EVENTS":
			return 37;
			break;
		case "BETALINGEN":
			return 49;
			break;
		case "BETALING_MARGES":
			return 99;
			break;
		case "BETALING_MARGE_TYPES":
			return 112;
			break;
		case "BETALING_METHODES":
			return 120;
			break;
		case "DIENSTEN":
			return 130;
			break;
		case "DIENST_STATUSSEN":
			return 155;
			break;
		case "EXPORTLIJNEN":
			return 163;
			break;
		case "FIN_AFSLUITINGEN":
			return 174;
			break;
		case "FIN_AFSLUITING_EXPORTLIJNEN":
			return 188;
			break;
		case "KASVERSLAG":
			return 198;
			break;
		case "TERUGBETALING_VOORSTELLEN":
			return 210;
			break;
		case "TERUGBETALING_VOORSTEL_BATCHES":
			return 244;
			break;
		case "VERKOOPLIJNEN":
			return 263;
			break;
		case "VERWACHTE_BETALINGEN":
			return 282;
			break;
		case "VERWACHTE_BETALING_STATUSSEN":
			return 326;
			break;
	}
}
function getUpperbound($tablename){
	switch ($tablename){
		case "AUDIT_EIGENSCHAPPEN":
			return 13;
			break;
		case "AUDIT_ENTITEITEN":
			return 28;
			break;
		case "AUDIT_EVENTS":
			return 42;
			break;
		case "BETALINGEN":
			return 91;
			break;
		case "BETALING_MARGES":
			return 104;
			break;
		case "BETALING_MARGE_TYPES":
			return 113;
			break;
		case "BETALING_METHODES":
			return 123;
			break;
		case "DIENSTEN":
			return 146;
			break;
		case "DIENST_STATUSSEN":
			return 156;
			break;
		case "EXPORTLIJNEN":
			return 167;
			break;
		case "FIN_AFSLUITINGEN":
			return 181;
			break;
		case "FIN_AFSLUITING_EXPORTLIJNEN":
			return 189;
			break;
		case "KASVERSLAG":
			return 202;
			break;
		case "TERUGBETALING_VOORSTELLEN":
			return 235;
			break;
		case "TERUGBETALING_VOORSTEL_BATCHES":
			return 253;
			break;
		case "VERKOOPLIJNEN":
			return 273;
			break;
		case "VERWACHTE_BETALINGEN":
			return 317;
			break;
		case "VERWACHTE_BETALING_STATUSSEN":
			return 327;
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
                                .$prevTableMob."@REVMGMT o WHERE NOT EXIST (SELECT ID FROM "
                                .$prevTabelOds." I where I.ID =O.ID);<br>";
                        $FORSql .= $prevTableMob."@REVMGMT y JOIN ".$prevTabelOds." z ON z.ID = y.ID "
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
