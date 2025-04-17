<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_REQUEST['candidateId'])&& isset($_REQUEST['taxcode'])){
    $response = removeTaxCodeAlocation($mysqli,$_REQUEST['candidateId'],$_REQUEST['taxcode']);
    generateNotification('outapay@outapay.com','','','Candidate tax no removed',DEFAULT_EMAIL,DOMAIN_NAME.' Financial Info Alert','<br><br>System User '.$_SESSION['userSession']. ' has removed tax no of candidate '.getCandidateFullName($mysqli,$_REQUEST['candidateId']).'('.$_REQUEST['candidateId'].') at '.date('Y-m-d H:i:s'),'','');
    echo $response;
}
?>