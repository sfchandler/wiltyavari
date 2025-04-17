<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$param = $_REQUEST['param'];
$positionid = $_REQUEST['positionid'];
$rosterStartDate = date('Y-m-d',strtotime($_REQUEST['rosterStartDate']));
$rosterEndDate = date('Y-m-d',strtotime($_REQUEST['rosterEndDate']));
$rosterOrder = $_REQUEST['rosterOrder'];
if($_REQUEST['action'] == 'GET'){
    $ps = explode('-',$param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo getRosterOrder($mysqli,$rosterStartDate,$rosterEndDate,$positionid,$deptId);
}elseif ($_REQUEST['action'] == 'UPDATE'){
    $ps = explode('-',$param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo updateRosterOrder($mysqli,$rosterOrder,$rosterStartDate,$rosterEndDate,$positionid,$deptId);
}
