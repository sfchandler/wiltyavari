<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientid = $_REQUEST['clientid'];
$positionid = $_REQUEST['positionid'];
$deptid = $_REQUEST['deptid'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];
$appStatus = $_REQUEST['appStatus'];
if(isset($clientid)) {
    echo getUpdatedTimeSheets($mysqli,$clientid,$candidateId,$positionid,$deptid,$startDate,$endDate,$appStatus);
}else{
    echo '<span>client not set</span>';
}

?>