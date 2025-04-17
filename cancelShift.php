<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$shiftid = $_REQUEST['shiftid'];
$shiftStatus = $_REQUEST['shiftStatus'];
$shiftNote = $_REQUEST['shiftNote'];
$consultantId = getConsultantId($mysqli,$_REQUEST['consultant']);

echo cancelShift($mysqli,$shiftid,$shiftStatus,$shiftNote,$consultantId,$_SESSION['userSession']);

?>