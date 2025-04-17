<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
date_default_timezone_set('Australia/Melbourne');
$currentDate = date('Y-m-d');
$supervisorClient = '147'; // oakleigh // 152 keon
$jobList = getTimeClockBySupervisorForPeriod($mysqli, $shiftStatus, $currentDate, $currentDate, $empSelected,$supervisorClient);
$mailBody = '';
$html='';
$htmlRoster = '';
if(sizeof($jobList)>0) {
    $htmlRoster = $htmlRoster.'<br><div><strong>Rostered casuals checked IN / NOT</strong></div>';
    $htmlRoster = $htmlRoster.'<table cellpadding="2" cellspacing="2" border="1">';
    $htmlRoster = $htmlRoster.'<th>EMPLOYEEID</th><th>EMPLOYER</th><th>EMPLOYEE NAME</th><th>SHIFT DAY</th><th>SHIFT DATE</th><th>CHECK IN</th><th>CHECK OUT</th><th>WORK BREAK</th>';
    $data = '';
    var_dump($jobList);
    foreach ($jobList as $rec){
        //if(empty($rec['checkIn'])) {
            $data = $data . '<tr><td>' . $rec['candidateId'] . '</td><td>'.getClientNameByClientId($mysqli,$rec['clientId']).'</td><td>'.getCandidateFullName($mysqli,$rec['candidateId']).'</td><td>'.$rec['shiftDay'] . '</td><td>' . $rec['shiftDate'] . '</td><td>' . $rec['checkIn'] . '</td><td>'.$rec['checkOut'].'</td><td>'.$rec['workBreak'].'</td></tr>';
        //}
    }
    $htmlRoster = $htmlRoster.$data.'</table>';
    $mailBody = $mailBody.$htmlRoster;
}else{
    $mailBody = $mailBody.'No Rostered records';
}

try {
    echo $mailBody;
    //generateMailNotification('TimeClock Notification - Check IN validation','', 'handovergroup@chandlerservices.com.au', $mailBody);
}catch (Exception $e){
    $e->getMessage();
}