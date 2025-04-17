<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canId = $_REQUEST['candid'];
$stateId = $_REQUEST['stid'];
$deptId = $_REQUEST['did'];
$clientId = $_REQUEST['clid'];
$status = $_REQUEST['status'];
$chUser = $_SESSION['userSession'];
if(!empty($canId)&&!empty($status)){
echo updateOHSViewedStatus($mysqli,$canId,$stateId,$deptId,$clientId,$status,$chUser);
}

