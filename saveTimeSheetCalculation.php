<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
$deptId = $_REQUEST['deptId'];
$earlyMorningTotal = $_REQUEST['earlyMorningTotal'];
$ordTotal= $_REQUEST['ordTotal'];
$aftTotal = $_REQUEST['aftTotal'];
$nightTotal = $_REQUEST['nightTotal'];
$rdoTotal = $_REQUEST['rdoTotal'];
$satTotal = $_REQUEST['satTotal'];
$sunTotal = $_REQUEST['sunTotal'];
$ovtTotal = $_REQUEST['ovtTotal'];
$dblTotal = $_REQUEST['dblTotal'];
$hldTotal = $_REQUEST['hldTotal'];
$hol_total = 0;
$satovtTotal = $_REQUEST['satovtTotal'];
$sunovtTotal = $_REQUEST['sunovtTotal'];
$povtTotal = $_REQUEST['povtTotal'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];
$wkendDate = $_REQUEST['wkendDate'];
$jobCode = getJobCodeByClientPosition($mysqli,$clientId,$positionId,$deptId);
$department = '';
if(!empty($clientId) && !empty($positionId) && !empty($startDate) && !empty($endDate) && !empty($wkendDate)) {
   echo saveTimeSheetCalculation($mysqli, $clientId, $positionId,$deptId,$jobCode, $earlyMorningTotal,$ordTotal, $aftTotal, $nightTotal,$rdoTotal, $satTotal, $sunTotal, $ovtTotal, $dblTotal, $hldTotal,$hol_total,$satovtTotal,$sunovtTotal, $povtTotal,$startDate, $endDate,$candidateId,$wkendDate);
}else{
    echo 'values not set';
}

?>