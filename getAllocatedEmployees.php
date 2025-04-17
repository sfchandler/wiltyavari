<?php
	require_once("includes/db_conn.php");
	require_once("includes/functions.php");
	
	/*$param = $_REQUEST['param'];*/
	$num_th = $_REQUEST['num_th'];
	$clientId = $_REQUEST['clientId'];
	$stateId = $_REQUEST['stateId'];
	$deptId = $_REQUEST['deptId'];
    $startDate = $_REQUEST['startDate'];
    $endDate = $_REQUEST['endDate'];
    $stWrkDate = $_REQUEST['stWrkDate'];
    $header = array();
	$header = $_REQUEST['headerGlobal'];
	$positionid = $_REQUEST['positionid'];
	$canId = $_REQUEST['candidateId'];
	$estatus = 1;
	if(isset($clientId) && isset($stateId) && isset($deptId) && isset($num_th)){
		/*$ps = explode('-',$param);
		$clientId = $ps[0];
		$stateId = $ps[1];
		$deptId = $ps[2];*/
        if(getClientStatus($mysqli,$clientId) == 'ACTIVE') {
            echo getAllocatedEmployees($mysqli, $clientId, $stateId, $deptId, $num_th, $header, $estatus, $positionid, $canId, $startDate,$endDate, $stWrkDate);
        }else{
            echo '<tr><td class="rosterCell" colspan="'.(count($header)+2).'" style="color: red; text-align: center;">CLIENT NOT ACTIVATED. Please contact Accounts department for client audit status</td></tr>';
        }
	}
?>