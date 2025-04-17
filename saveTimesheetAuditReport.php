<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$weekEndingDateStart = $_POST['weekEndingDateStart'];
$reportPath = $_POST['reportPath'];
$clientArray = explode(',',$_REQUEST['clientArray']);
foreach ($clientArray as $key => $value) {
    if(!validateClientSummarySave($mysqli,$weekEndingDateStart,$value)) {
        $response = updateClientSummary($mysqli, $value, $weekEndingDateStart);
    }else{
        $response = 'Records are existing';
    }
}
saveTimesheetAuditReport($mysqli,$weekEndingDateStart,$reportPath);
echo getTimeSheetAuditReportsList($mysqli);