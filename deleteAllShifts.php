<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
error_reporting(E_ALL);
ini_set('display_errors', true);
$canid = $_REQUEST['canid'];
$clid = $_REQUEST['clid'];
$stid = $_REQUEST['stid'];
$did = $_REQUEST['did'];
$strdate = $_REQUEST['strdate'];
$enddate = $_REQUEST['enddate'];
$consultantId = getConsultantId($mysqli,$_REQUEST['consultant']);
$currentUser = $_SESSION['userSession'];
echo deleteAllShifts($mysqli,$canid,$clid,$stid,$did,$strdate,$enddate,$consultantId,$currentUser);
?>