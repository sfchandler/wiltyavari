<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$candidateEmail = getEmployeeEmail($mysqli,$_REQUEST['rCanId']);
$consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_REQUEST['consultantId']));
updateEmployeeAllocationOHS($mysqli,$_REQUEST['rCanId'],$_REQUEST['clientId'],$_REQUEST['stateId'],$_REQUEST['deptId']);
generateNotification($candidateEmail,$consultantEmail,'','OH&S Questionnaire Submission',DEFAULT_EMAIL,DOMAIN_NAME,$_REQUEST['smsText'],'','');