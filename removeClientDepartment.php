<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientId = $_REQUEST['clientId'];
$stateId = $_REQUEST['stateId'];
$departmentId = $_REQUEST['departmentId'];
if(removeClientDepartment($mysqli,$departmentId,$clientId, $stateId)){
    updateUserActivityLog($mysqli,$_SESSION['userSession'],$_SERVER['REMOTE_ADDR'],'CLIENT DEPARTMENTS','','DELETE CLIENT DEPARTMENT','Client Department Deleted by '.$_SESSION['userSession'].' at '.date('Y-m-d H:i:s').'. Client ID'.$clientId.' State ID '.$stateId.' Department ID '.$departmentId);
    echo listDepartments($mysqli,'');
}else{
	echo '<tr><td colspan="4"></td></tr>';
}
