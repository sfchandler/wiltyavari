<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$candidateEmail = getEmployeeEmail($mysqli,$_REQUEST['rCanId']);
$consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_REQUEST['consultantId']));
generateNotification($candidateEmail,$consultantEmail,'','App Version Check Sent',DEFAULT_EMAIL,DOMAIN_NAME,$_REQUEST['smsText'],'','');