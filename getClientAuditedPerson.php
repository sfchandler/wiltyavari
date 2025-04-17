<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientId = $_REQUEST['clientId'];
$text = getClientLastAuditedPerson($mysqli,$clientId);
$currentAuditStatus = getClientAuditStatus($mysqli,$clientId);
if(!empty($text)) {
    $exp = explode('@',$text);
    $chUser = $exp[0];
    $auditStatus = $exp[1];
    if($auditStatus == '1'){
        $auditStatus = 'COMPLETE';
    }else{
        $auditStatus = 'INCOMPLETE';
    }
    $auditedTime = $exp[2];
    echo $chUser.' has set audit status to '.$auditStatus.' on '.$auditedTime;
}else{
    echo 'Audit status set to '.$currentAuditStatus.' for past/new record by System. Please check file and update!';
}
?>