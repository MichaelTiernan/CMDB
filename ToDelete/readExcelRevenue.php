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
    }
}
