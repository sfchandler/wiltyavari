<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
$param = $_REQUEST['param'];
$supervisorName = $_REQUEST['supervisorName'];
$supervisorPhone = $_REQUEST['supervisorPhone'];
$supervisorEmail = $_REQUEST['supervisorEmail'];
$supervisorId = $_REQUEST['supervisorId'];
$deleteStatus = $_REQUEST['deleteStatus'];
$supervisorPassword = $_REQUEST['supervisorPassword'];
$status = $_REQUEST['status'];
$consId = getConsultantId($mysqli,$_SESSION['userSession']);
$consultantEmail = getConsultantEmail($mysqli,$consId);
if($param <> 'None' && isset($param) && isset($supervisorName) && isset($supervisorPhone) && isset($supervisorEmail) && isset($supervisorPassword) && ($status <> 'update')) {
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo addSupervisorDetails($mysqli,$supervisorName,$supervisorPhone,$supervisorEmail,$clientId,$stateId,$deptId,$supervisorPassword,$consultantEmail);
}else if($param <> 'None' && isset($param) && isset($supervisorName) && isset($supervisorPhone) && isset($supervisorEmail) && isset($supervisorPassword) && isset($supervisorId) && ($status == 'update')){
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo updateSupervisorDetails($mysqli,$supervisorId,$supervisorName,$supervisorPhone,$supervisorEmail,$clientId,$stateId,$deptId,$supervisorPassword,$consultantEmail);
}else if($deleteStatus == '1' && $param <> 'None' && isset($param) && ($status <> 'update')){
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo removeSupervisor($mysqli,$supervisorId,$clientId,$stateId,$deptId);
}
?>