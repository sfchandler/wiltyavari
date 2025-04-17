<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientid = $_REQUEST['clientid'];
$positionid = $_REQUEST['positionid'];
$deptId = $_REQUEST['deptId'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];
$jobCode = getJobCodeByClientPosition($mysqli, $clientid, $positionid, $deptId);
$payRule = getPayruleByJobCode($mysqli,$jobCode);
$bulkFilter = $_REQUEST['bulkFilter'];
/*if($bulkFilter == '1'){
    //echo getTimeSheetData($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
    echo getTimeSheetBulkData($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
}else */
if(isset($clientid) && isset($positionid) && !empty($candidateId)) {
    if($jobCode == 'JBC618'){
        echo getDaviesTimeSheetData($mysqli, $clientid, $candidateId, $positionid, $jobCode, $payRule, $startDate, $endDate);
    }else {
        echo getTimeSheetData($mysqli, $clientid, $candidateId, $positionid, $jobCode, $payRule, $startDate, $endDate);
    }
    //echo payruleProcessing($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
}else if(isset($clientid) && isset($positionid)&& empty($candidateId)){
    if($jobCode == 'JBC618'){
        echo getDaviesTimeSheetData($mysqli, $clientid, $candidateId, $positionid, $jobCode, $payRule, $startDate, $endDate);
    }else {
        echo getTimeSheetData($mysqli, $clientid, $candidateId, $positionid, $jobCode, $payRule, $startDate, $endDate);
    }
        //echo payruleProcessing($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
}
else{
    echo '<span>client/Employee not set</span>';//'.$clientid.$positionid.$candidateId.'
}

?>