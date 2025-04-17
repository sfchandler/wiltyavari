<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$shiftid = $_REQUEST['shiftid'];
$eshiftDate = $_REQUEST['eshDate'];
$eclid = $_REQUEST['eclid'];
$estid = $_REQUEST['estid'];
$edid = $_REQUEST['edid'];
$ecanid = $_REQUEST['ecanid'];
$eshiftStart = $_REQUEST['eshiftStart'];
$eshiftEnd = $_REQUEST['eshiftEnd'];
$ebreak = $_REQUEST['eworkBreak'];
$enote = $_REQUEST['enote'];
$addressId = $_REQUEST['addressId'];
$eshiftCallStatus = $_REQUEST['eshiftCallStatus'];

$consultantId = getConsultantId($mysqli, $_SESSION['userSession']);
if($_REQUEST['eshiftCallStatus'] != 'None') {
    $shiftStatus = $_REQUEST['eshiftCallStatus'];
}else{
    if($_REQUEST['shiftStatus'] == 'on' || $_REQUEST['shiftStatus'] == 'CONFIRMED'){
        $shiftStatus = 'CONFIRMED';
    }else{
        $shiftStatus = 'OPEN';
    }
}

echo modifyAndDisplayShift($mysqli,$shiftid,$eshiftDate,$eclid,$estid,$edid,$ecanid,$eshiftStart,$eshiftEnd,$ebreak,$enote,$shiftStatus,$addressId,$consultantId,$_SESSION['userSession']);

?>