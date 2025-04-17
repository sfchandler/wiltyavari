<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$rCanId = $_REQUEST['rCanId'];
$shiftdate = $_REQUEST['shiftdate'];
$client = $_REQUEST['client'];
$clientId = $_REQUEST['clientId'];
$shiftDay = $_REQUEST['shiftDay'];
$shiftstart = $_REQUEST['shiftstart'];
$shiftend = $_REQUEST['shiftend'];
$consultant = $_REQUEST['consultant'];
$did = $_REQUEST['did'];
$department = getDepartmentById($mysqli,$did);
if(!empty($rCanId)){
    $empName = getNickNameById($mysqli,$rCanId);
    if(empty($empName)){
        $empName = getCandidateFullName($mysqli,$rCanId);
    }
    if(pinCheck($clientId)){
        $pinNo = "(PIN: ".getPINNoById($mysqli,$rCanId).")";
    }else{
        $pinNo = " ";
    }
}
echo getShiftTimeInfo($empName,$pinNo,$shiftdate, $client,$clientId,$department, $shiftDay,$shiftstart,$shiftend,$consultant,$rCanId);