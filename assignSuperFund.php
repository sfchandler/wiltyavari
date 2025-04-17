<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['transCode'] <> 'None' || $_REQUEST['candidateId'] <> ''){
    $status = assignSuperFundToCandidate($mysqli, $_REQUEST['candidateId'], $_REQUEST['transCode']);
    generateNotification(ACCOUNTS_EMAIL,'','','Candidate Super details assigned',DEFAULT_EMAIL,DOMAIN_NAME.'Financial Info Alert','<br><br>System User '.$_SESSION['userSession']. ' has assigned super fund/super member no of candidate '.getCandidateFullName($mysqli,$_REQUEST['candidateId']).'('.$_REQUEST['candidateId'].') at '.date('Y-m-d H:i:s'),'','');
    echo $status;
}else{
    echo 'NONE';
}
?>