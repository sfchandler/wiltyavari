<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/04/2019
 * Time: 12:53 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
date_default_timezone_set('Australia/Melbourne');
$currentDate = date('Y-m-d');
$supervisorClient = '158'; // Auscript
$shiftStatus = 'N';
$jobList = getTimeClockBySupervisorForPeriod($mysqli, $shiftStatus, $currentDate, $currentDate, $empSelected,$supervisorClient);
$mailBody = '';
$html='';
$htmlRoster = '';
$loginLink = '<a href="'.DOMAIN_URL.'/supervisor/">Please click here to login and confirm</a>';
if(sizeof($jobList)>0) {
    $htmlRoster = $htmlRoster.'<br><div><strong>Casual Clock IN/OUT</strong></div><br>'.$loginLink.'<br>';
    $htmlRoster = $htmlRoster.'<br><table cellpadding="2" cellspacing="2" border="1">';
    $htmlRoster = $htmlRoster.'<th>EMPLOYEE NAME</th><th>EMPLOYEE MOBILE</th><th>SHIFT DAY</th><th>SHIFT DATE</th><th>CHECK IN</th><th>CHECK OUT</th><th>WORK BREAK</th><th>WORK LOCATION</th><th>EMPLOYEEID</th>';
    $data = '';
    foreach ($jobList as $rec){
        //if(empty($rec['checkIn'])) {
        $data = $data . '<tr><td>'.getCandidateFullName($mysqli,$rec['candidateId']).'</td><td>'.getCandidateMobileNoByCandidateId($mysqli,$rec['candidateId']).'</td><td>'.$rec['shiftDay'] . '</td><td>' . $rec['shiftDate'] . '</td><td>' . $rec['checkIn'] . '</td><td>'.$rec['checkOut'].'</td><td>'.$rec['workBreak'].'</td><td>'.getClientNameByClientId($mysqli,$rec['clientId']).'</td><td>' . $rec['candidateId'] . '</td></tr>';
        //}
    }
    $htmlRoster = $htmlRoster.$data.'</table><br>';
    $mailBody = $mailBody.$htmlRoster;

}else{
    $mailBody = $mailBody.'No Rostered records';
}

try {
    generateMailNotification('Electronic Timesheet','outapay@outapay.com',' ', $mailBody);//'handovergroup@chandlerservices.com.au'
}catch (Exception $e){
    $e->getMessage();
}