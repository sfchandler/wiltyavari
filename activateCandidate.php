<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$canId = $_REQUEST['canId'];
$status = $_REQUEST['status'];
$auditStatus = $_REQUEST['auditStatus'];
$chUser = $_SESSION['userSession'];
$activate_note = $_REQUEST['activate_note'];
$fullName = getCandidateFullName($mysqli, $canId);
$rows = getShiftDataAlertForCandidate($mysqli,date('Y-m-d'),date('Y-m-d', strtotime('+2 years')),$canId);
$tbl = '';
if (!empty($rows)) {
    $tbl = $tbl.'<br>  The profile '.$canId.' ('.$fullName.') has been inactivated/audit incomplete. Kindly note the staff has been rostered for below future shifts. <br>';
    $tbl = $tbl.'<table border="1" cellspacing="2" cellpadding="2">
                <thead>
                  <tr>
                    <th>Shift Date</th>
                    <th>Shift Day</th>
                    <th>Client</th>
                    <th>State</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Shift Start</th>
                    <th>Shift End</th>
                    <th>Status</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                  </tr>
                </thead>
                <tbody id="tblBody">
                '.$rows.'     
                </tbody>
              </table>';
}
if(!empty($canId)&&!empty($status)){
    if($status == 'INACTIVE') {
        $mailbody = '<br>Hi All, <br><br> Audit check for candidate ' . $fullName . '(' . $canId . ') is set to '.$status.' status by system user ' . $chUser . ' at ' . date('Y-m-d H:i:s');
        generateNotification('outapay@outapay.com', 'outapay@outapay.com', '', 'Audit Check Uncompleted by Accounts Division', DEFAULT_EMAIL, DOMAIN_NAME, $mailbody.$tbl, '', '');
    }
    echo updateEmployeeStatus($mysqli,$canId,$status,$activate_note.' - Actioned by '.$chUser.' on '.date('Y-m-d H:i:s'));
}elseif (!empty($canId)&&!empty($auditStatus)){
    $response = updateAuditStatus($mysqli,$canId,$auditStatus,$chUser);
    if($auditStatus == 'AUDIT COMPLETE') {
        $mailbody = '<br>Hi All, <br><br> Audit check for candidate ' . $fullName . '(' . $canId . ') is set to '.$auditStatus.' status by Accounts Division system user ' . $chUser . ' at ' . date('Y-m-d H:i:s');
        generateNotification('outapay@outapay.com', 'outapay@outapay.com', '', 'Audit Check Completed by Accounts Division', DEFAULT_EMAIL, DOMAIN_NAME, $mailbody, '', '');
    }else if($auditStatus == 'AUDIT INCOMPLETE'){
        $mailbody = '<br>Hi All, <br><br> Audit check for candidate ' . $fullName . '(' . $canId . ') is set to '.$auditStatus.' status by Accounts Division system user ' . $chUser . ' at ' . date('Y-m-d H:i:s');
        generateNotification('outapay@outapay.com', 'outapay@outapay.com', '', 'Audit Check Uncompleted by Accounts Division', DEFAULT_EMAIL, DOMAIN_NAME, $mailbody.$tbl, '', '');
    }
    echo $response;
}
