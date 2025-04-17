<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
$candidateEmail = getEmployeeEmail($mysqli,$_REQUEST['rCanId']);
$consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_REQUEST['consultantId']));
updateCustomerSurveySent($mysqli,$_REQUEST['rCanId'],date('Y-m-d H:i:s'));
generateNotification($candidateEmail,$consultantEmail,'','Customer Survey Request',DEFAULT_EMAIL,DOMAIN_NAME,$_REQUEST['smsText'],'','');