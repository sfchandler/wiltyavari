<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
if(!empty($_SESSION['userSession'])) {
    $sid = $_SESSION['sid'];
    $act = $_REQUEST['act'];
    $alertMe = $_REQUEST['alertMe'];
    $smsAccount = $_REQUEST['smsAccount'];
    $smsText = $_REQUEST['smsText'];
    $countryCode = '+61';
    $consultantId = getConsultantId($mysqli, $_SESSION['userSession']);
    $stmt = $mysqli->prepare("SELECT firstName,lastName,candidateId,mobileNo FROM tmpsmslist WHERE sessionid = ?") or die($mysqli->error);
    $stmt->bind_param("s", $mysqli->real_escape_string($sid)) or die($mysqli->error);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName, $candidateId, $mobileNo) or die($mysqli->error);
    $stmt->store_result();
    $nrows = $stmt->num_rows;
    $phoneNumbers = array();
    if ($nrows > 0) {
        while ($stmt->fetch()) {
            $phoneNumbers[] = array('FULLNAME' => $lastName . ', ' . $firstName, 'CANDIDATEID' => $candidateId, 'MOBILE' => str_replace(' ', '', $mobileNo));
        }
    }
    $stmt->free_result();
    if ($smsAccount == '1') {
        //Cellcast
        $from = '61481076330';
        $validity = 0;
        $direction = 'Outgoing';
        $ins = $mysqli->prepare("INSERT INTO smslog(message_id,
												consultantId,
												sentTimeStamp,
												candidateId,
												recipientName,
												recipientNumber,
												smsMessage,
												smsReturnData,
												sent,
												unitCost,
												smsActivity,
												smsAccount,
												smsSender,
												alertMe,
												errorDescription,
												direction)
										VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($mysqli->error);
        if (!empty($phoneNumbers)) {
            foreach ($phoneNumbers as $data) {
                $mobile = $data['MOBILE'];
                $response = sendCellCastSMS($smsText, rawurlencode($mobile), rawurlencode($from));
                $recipientName = $data['FULLNAME'];
                $candidateId = $data['CANDIDATEID'];
                $rsData = json_decode($response, true);
                $messageId = $rsData['result']['data']['messages'][0]['message_id'];
                $numRecipients = $rsData['result']['data']['messages'][0]['to'];
                $deliveryStatus = $rsData['result']['data']['success_number'];
                $responseStatus = $rsData['result']['meta']['status'];
                $errorDescription = $rsData['result']['meta']['status'];
                $sms = $rsData['result']['data']['messages'][0]['body'];
                $cost = $rsData['result']['data']['credits_used'];
                $sentDateTime = date("Y-m-d H:i:s");
                try {
                    $ins->bind_param("sissssssssssssss", $messageId, $consultantId, $sentDateTime, $candidateId, $recipientName, $mobile, $smsText, $responseStatus, $deliveryStatus, $cost, $act, $smsAccount, $dedicatedNumber, $alertMe, $errorDescription, $direction) or die($mysqli->error);
                    if ($ins->execute()) {
                        $status = 'INSERTED';
                    } else {
                        $status = 'ERROR' . $mysqli->error;
                    }
                    $nrows = $ins->affected_rows;
                    if ($nrows == '1') {
                        $ins->free_result();
                        $del = $mysqli->prepare("DELETE FROM tmpsmslist WHERE sessionid = ?");
                        $del->bind_param("s", $sid) or die($mysqli->error);
                        $del->execute();
                        session_regenerate_id();
                    } else {
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
            echo 'MSGSENT';
        } else {
            echo 'NORECIPIENTS';
        }
    }
}else{
    echo 'SESSIONEXP';
}
?>