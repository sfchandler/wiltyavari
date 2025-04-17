<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['tfn'] <> '' || $_REQUEST['candidateId'] <> ''){
    $response = updateCandidateTFN($mysqli, $_REQUEST['candidateId'], $_REQUEST['tfn']);
    generateNotification('outapay@outapay.com','','','Candidate Tax details Added/Updated',DEFAULT_EMAIL,DOMAIN_NAME.' Financial Info Alert','<br><br>System User '.$_SESSION['userSession']. ' has changed/updated tax no of candidate '.getCandidateFullName($mysqli,$_REQUEST['candidateId']).'('.$_REQUEST['candidateId'].') at '.date('Y-m-d H:i:s'),'','');
    echo $response;
}
?>