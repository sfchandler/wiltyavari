<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
date_default_timezone_set('Australia/Melbourne');
$currentDate = date('Y-m-d');

//$checkedInCasuals = getCheckedInCasualsAll($mysqli,$currentDate);
$rosteredCasuals = getRosteredConfirmedCasualsAll($mysqli,$currentDate);
$mailBody = '';
$html='';
$htmlRoster = '';
if(sizeof($rosteredCasuals)>0) {
    $htmlRoster = $htmlRoster.'<br><div><strong>Rostered casuals who NOT checked IN</strong></div><table cellpadding="2" cellspacing="2" border="1"><th>CLIENT</th><th>EMPLOYEEID</th><th>FIRSTNAME</th><th>LASATNAME</th><th>EMPLOYEE MOBILE</th><th>SHIFT DATE</th><th>SHIFT START</th><th>CHECK IN</th><th>State</th><th>SHIFT END</th><th>SHIFT DAY</th><th>POSITION</th><th>DEPARTMENT</th>';
    $data = '';
    $perthRosterTime = '';
    foreach ($rosteredCasuals as $rec){
        if(empty($rec['checkIn'])) {
            if(!displayNoPhoneIndicator($mysqli,$rec['candidateId'])) {
                $data = $data . '<tr ';
                if($rec['clientId'] == 330){
                    $data = $data.'style="background-color: orangered"';
                    /*$melbourneRosterTime = $rec['shiftDate'].' '.$rec['shiftStart'].':00';
                    $timestamp = strtotime($melbourneRosterTime);
                    $time = $timestamp - (2 * 60 * 60);
                    $perthRosterTime = date("H:i", $time);*/
                }

                $data = $data.'><td>' . $rec['client'] . '</td><td>' . $rec['candidateId'] . '</td><td>' . $rec['firstName'] . '</td><td>' . $rec['lastName'] . '</td><td>' . getCandidateMobileNoByCandidateId($mysqli, $rec['candidateId']) . '</td><td>' . $rec['shiftDate'] . '</td><td>' . $rec['shiftStart'] . '</td><td style="background-color: greenyellow">' . $rec['checkIn'] . '</td><td>';
                if($rec['clientId'] == 330){
                    $data = $data . $rec['state'];
                }
                $data = $data.'</td><td>' . $rec['shiftEnd'] . '</td><td>' . $rec['shiftDay'] . '</td><td>' . $rec['positionName'] . '</td><td>' . $rec['department'] . '</td></tr>';
            }
        }
    }
    $htmlRoster = $htmlRoster.$data.'</table>';
    $mailBody = $mailBody.$htmlRoster;
}else{
    $mailBody = $mailBody.'No Rostered records';
}

try {
    generateMailNotification('TimeClock Notification - Check IN validation','', 'outapay@outapay.com', $mailBody);
}catch (Exception $e){
    $e->getMessage();
}