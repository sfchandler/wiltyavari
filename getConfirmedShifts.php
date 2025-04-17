<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 1000000000);

$clientid = $_REQUEST['clientid'];
$positionid = $_REQUEST['positionid'];
$deptid = $_REQUEST['deptid'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];

if(isset($clientid)) {
    echo getConfirmedShifts($mysqli,$clientid,$candidateId,$positionid,$deptid,$startDate,$endDate);
}else{
    echo '<span>client not set</span>';
}

?>