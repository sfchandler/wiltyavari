<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$response = processSuperMemberNo($mysqli,$_REQUEST['candidateId'],$_REQUEST['memberNo']);
generateNotification('outapay@outapay.com','','','Candidate Super details changed',DEFAULT_EMAIL,DOMAIN_NAME.' Financial Info Alert','<br><br>System User '.$_SESSION['userSession']. ' has changed/updated super fund/super member no of candidate '.getCandidateFullName($mysqli,$_REQUEST['candidateId']).'('.$_REQUEST['candidateId'].') at '.date('Y-m-d H:i:s'),'','');
echo $response;