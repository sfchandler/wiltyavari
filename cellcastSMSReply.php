<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$json = file_get_contents('php://input');
$jsonData = json_decode($json, true);

$from = '';
$body = '';
$received_at = '';
$message_id = '';
$custom_string = '';
$type = '';
foreach ($jsonData as $js)
{
    $from = $js['from'];
    $body = $js['body'];
    $received_at = $js['received_at'];
    $message_id = $js['message_id'];
    $custom_string = $js['custom_string'];
    $type = $js['type'];
}

$str = substr($from, 2);
$senderMobile = '0'.$str;
$datetime_entry = $received_at;
$response = $body;
$recipientNumber = $from;
$candidateId = getCandidateIdByMobileNo($mysqli,$senderMobile);
$consultantId = getConsultantIdByMessageId($mysqli,$message_id);
$recipientName = getRecipientNameByMessageId($mysqli,$message_id);
if(empty($recipientName)){
    $recipientName = getCandidateFormatedFullName($mysqli,$candidateId);
}
$status = '';
$direction = 'Incoming';
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
										VALUES(?,?,?,?,?,?,?,?,?,?)") or die($mysqli->error);
    $ins->bind_param("sissssssss",$message_id,$consultantId,$datetime_entry,$candidateId,$recipientName,$recipientNumber,$response,$senderMobile,$received_at,$direction) or die($mysqli->error);
    if($ins->execute()){
        $status= 'INSERTED';
    }else{
        $status = 'ERROR'.$mysqli->error;
    }
    $nrows = $ins->affected_rows;
    if($status == 'INSERTED'){
        echo generateNotification('outapay@outapay.com','swarnajithf@chandlerpersonnel.com.au','','SMS REPLY :: Via Cellcast',DEFAULT_EMAIL,DOMAIN_NAME,'<br>'.$recipientName.' ('.$candidateId.') replied to your recent SMS at '.$datetime_entry.'<br>Sender Mobile:'.$senderMobile.'<br>Text Message :'.$response,'','');

    }else{

        echo generateNotification('outapay@outapay.com','swarnajithf@chandlerpersonnel.com.au','','SMS REPLY  - Not Recorded',DEFAULT_EMAIL,DOMAIN_NAME,'<br><br>'.$recipientName.'('.$candidateId.') replied to your recent SMS at '.$datetime_entry.'<br>Sender Mobile:'.$senderMobile.'<br>Text :'.$response.$status,'','');
    }
}
?>