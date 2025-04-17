<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if($_REQUEST['action'] == 'scheduling'){
    echo getDepartmentByClientIdStateId($mysqli,$_REQUEST['clientId'], $_REQUEST['stateId']);
}elseif($_REQUEST['action'] == 'jobdesc') {
    $data = explode('-',$_REQUEST['department_info']);
    $clientId = $data[0];
    $stateId = $data[1];
    $deptId = $data[2];
    echo getDepartmentJobDescription($mysqli,$clientId, $stateId,$deptId,$_REQUEST['posId']);
}else {
    echo getDepartmentsByClientId($mysqli, $_REQUEST['clientId'], $_REQUEST['stateId']);
}
?>