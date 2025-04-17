<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_POST['clientId'];
$stateId = $_POST['stateId'];
$departmentId = $_POST['departmentId'];
$note = $_POST['note'];
$numCasuals = $_POST['numCasuals'];
$action = $_POST['action'];
$id = $_POST['id'];
$status = $_POST['status'];
$jobOrderId = $_POST['jobOrderId'];
$consultantId = getConsultantId($mysqli,$_SESSION['userSession']);
if($action == 'Add'){
    echo AddJobOrder($mysqli,$clientId,$stateId,$departmentId,$note,$numCasuals,$consultantId);
}elseif ($action == 'View'){
    echo getJobOrders($mysqli);
}elseif ($action == 'Update'){
    echo updateJobOrder($mysqli,$id,$consultantId,$status);
}elseif ($action == 'LogView'){
    echo getJobOrderLog($mysqli,$id);
}elseif ($action == 'Note'){
    $additionalNote = $_POST['additionalNote'];
    echo updateJobOrderAdditionalNote($mysqli,$jobOrderId,$additionalNote,$consultantId);
}