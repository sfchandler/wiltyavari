<?php 
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

	$message_id = $_GET['message_id'];
	$str = substr($_GET['mobile'], 2);
	$senderMobile = '0'.$str;
	$datetime_entry = date('Y-m-d H:i:s');
	$response = $_GET['response']; 
	$recipientNumber = $_GET['longcode'];
	$candidateId = getCandidateIdByMobileNo($mysqli,$senderMobile);
	$consultantId = getConsultantIdByMessageId($mysqli,$message_id);
    $recipientName = getCandidateFullName($mysqli,$candidateId);
    $direction = 'Incoming';
    SMSReplyMail($recipientName,$candidateId,$datetime_entry,$senderMobile,$response,$recipientNumber,$message_id);

    if(!empty($message_id) && !empty($response)){
        $ins = $mysqli->prepare("INSERT INTO smslog(message_id,
                                                    consultantId,
                                                    sentTimeStamp,
                                                    candidateId,
                                                    recipientName,
                                                    recipientNumber,
                                                    smsMessage,
                                                    smsSender,
                                                    timeRecieved,
                                                    direction)
                                            VALUES(?,?,?,?,?,?,?,?,NOW(),?)") or die($mysqli->error);
        $ins->bind_param("sisssssss",$message_id,$consultantId,$datetime_entry,$candidateId,$recipientName,$recipientNumber,$response,$senderMobile,$direction) or die($mysqli->error);
        $ins->execute();
        $nrows = $ins->affected_rows;
    }
    
?>