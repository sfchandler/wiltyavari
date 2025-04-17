<?php
session_start();
require_once ("includes/db_conn.php");
require_once ("includes/functions.php");
if(isset($_REQUEST['param'])&& isset($_REQUEST['positionid']) && ($_REQUEST['status'] == 'Add')){
    $param = $_REQUEST['param'];
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    $positionId = $_REQUEST['positionid'];
    $status = addClientPosition($mysqli,$clientId,$stateId,$deptId,$positionId);
    updateUserActivityLog($mysqli,$_SESSION['userSession'],$_SERVER['REMOTE_ADDR'],'CLIENT DEPARTMENTS','','ADD CLIENT POSITION','Client Position Added by '.$_SESSION['userSession'].' at '.date('Y-m-d H:i:s').'. Client ID'.$clientId.' State ID '.$stateId.' Department ID '.$deptId.' position ID'.$positionId);
    echo $status;
}else if(($_REQUEST['status'] == 'Delete') && isset($_REQUEST['posid']) && isset($_REQUEST['clid']) && isset($_REQUEST['stid']) && isset($_REQUEST['depid'])){
    $status = deleteClientPosition($mysqli,$_REQUEST['posid'],$_REQUEST['clid'],$_REQUEST['stid'],$_REQUEST['depid']);
    updateUserActivityLog($mysqli,$_SESSION['userSession'],$_SERVER['REMOTE_ADDR'],'CLIENT DEPARTMENTS','','DELETE CLIENT POSITION','Client Position Deleted by '.$_SESSION['userSession'].' at '.date('Y-m-d H:i:s').'. Client ID'.$_REQUEST['clid'].' State ID '.$_REQUEST['stid'].' Department ID '.$_REQUEST['depid'].' position ID'.$_REQUEST['posid']);
    echo $status;
}
?>