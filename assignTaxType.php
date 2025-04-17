<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['taxcode'] <> 'None' || $_REQUEST['candidateId'] <> ''){
    $response = assignTaxCodeToCandidate($mysqli, $_REQUEST['candidateId'], $_REQUEST['taxcode']);
    generateNotification('outapay@outapay.com','','','Candidate Tax code assigned',DEFAULT_EMAIL,DOMAIN_NAME.' Financial Info Alert','<br><br>System User '.$_SESSION['userSession']. ' has assigned tax code of candidate '.getCandidateFullName($mysqli,$_REQUEST['candidateId']).'('.$_REQUEST['candidateId'].') at '.date('Y-m-d H:i:s'),'','');
    echo $response;
}
?>