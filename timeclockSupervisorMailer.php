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
$clientId = 36;// Monash

$checkedInCasuals = getCheckedInCasuals($mysqli,$currentDate,$clientId);
$rosteredCasuals = getRosteredConfirmedCasuals($mysqli,$currentDate,$clientId);
$mailBody = '';
$html='';
/*if(sizeof($checkedInCasuals)>0) {
    $html = $html.'<div><strong>Checked IN Casuals List</strong></div><table cellpadding="2" cellspacing="2" border="1"><th>CLIENT</th><th>SHIFTID</th><th>EMPLOYEEID</th><th>SHIFT DATE</th><th>CHECKIN TIME</th><th>ROSTERED START TIME</th><th>WORK BREAK</th><th>JOBCODE</th><th>POSITION</th><th>DEPARTMENT</th><th>FIRSTNAME</th><th>LASATNAME</th><th>SUPERVISOR</th>';
    foreach ($checkedInCasuals as $data){
        $html = $html.'<tr><td>'.$data['client'].'</td><td>'.$data['shiftId'].'</td><td>'.$data['candidateId'].'</td><td>'.$data['shiftDate'].'</td><td>'.$data['checkIn'].'</td><td>'.$data['shiftStart'].'</td><td>'.$data['workBreak'].'</td><td>'.$data['jobCode'].'</td><td>'.$data['positionName'].'</td><td>'.$data['department'].'</td><td>'.$data['firstName'].'</td><td>'.$data['lastName'].'</td><td>'.getCandidateFullName($mysqli,$data['supervisor']).'</td></tr>';
    }
    $html = $html.'</table>';
    $mailBody = $mailBody.$html;
}else{
    $mailBody = $mailBody.'NIL';
}*/
$htmlRoster = '';
if(sizeof($rosteredCasuals)>0) {
    $htmlRoster = $htmlRoster.'<br><div><strong>Rostered casuals who didnt check IN</strong></div><table cellpadding="2" cellspacing="2" border="1"><th>CLIENT</th><th>SHIFTID</th><th>EMPLOYEEID</th><th>SHIFT DATE</th><th>SHIFT START</th><th>CHECK IN</th><th>SHIFT END</th><th>SHIFT BREAK</th><th>SHIFT DAY</th><th>JOBCODE</th><th>POSITION</th><th>DEPARTMENT</th><th>FIRSTNAME</th><th>LASATNAME</th><th>SUPERVISOR</th>';
    $data = '';
    foreach ($rosteredCasuals as $rec){
        if(!empty($rec['checkIn'])) {
            $data = $data . '<tr><td>' . $rec['client'] . '</td><td>' . $rec['shiftId'] . '</td><td>' . $rec['candidateId'] . '</td><td>' . $rec['shiftDate'] . '</td><td>' . $rec['shiftStart'] . '</td><td style="background-color: greenyellow">' . $rec['checkIn'] . '</td><td>' . $rec['shiftEnd'] . '</td><td>' . $rec['workBreak'] . '</td><td>' . $rec['shiftDay'] . '</td><td>' . $rec['jobCode'] . '</td><td>' . $rec['positionName'] . '</td><td>' . $rec['department'] . '</td><td>' . $rec['firstName'] . '</td><td>' . $rec['lastName'] . '</td><td>' . getSupervisorNameById($mysqli, $rec['supervicerId']) . '</td></tr>';
        }
    }
    $htmlRoster = $htmlRoster.$data.'</table>';
    $mailBody = $mailBody.$htmlRoster;
}else{
    $mailBody = $mailBody.'No Rostered records';
}

try {
    generateMailNotification('TimeClock Notification - Check IN validation','', 'swarnajithf@chandlerservices.com.au', $mailBody);
}catch (Exception $e){
    $e->getMessage();
}