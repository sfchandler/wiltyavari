<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jsonData = json_decode(file_get_contents('php://input'), true);
$message_id = $jsonData['message_id'];
$str = substr($jsonData['source_number'], 3);
$senderMobile = '0'.$str;
$datetime_entry = $jsonData['submitted_date'];
$response = $jsonData['content'];
$recipientNumber = $jsonData['destination_number'];
$candidateId = getCandidateIdByMobileNo($mysqli,$senderMobile);
$consultantId = getConsultantIdByMessageId($mysqli,$message_id);
$recipientName = getRecipientNameByMessageId($mysqli,$message_id);
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
										VALUES(?,?,?,?,?,?,?,?,NOW(),?)") or die($mysqli->error);
    $ins->bind_param("sisssssss",$message_id,$consultantId,$datetime_entry,$candidateId,$recipientName,$recipientNumber,$response,$senderMobile,$direction) or die($mysqli->error);
    $ins->execute();
    $nrows = $ins->affected_rows;
    if($nrows == '1'){
        require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
        $mail = new PHPMailer();
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = DEFAULT_EMAIL;
        $mail->Password = DEFAULT_EMAIL_PASSWORD;
        $mail->SMTPSecure = "tls";
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host = "outlook.office365.com";
        $mail->LE = "\r\n";
        $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
        $subject = 'SMS REPLY Via MessageMedia';
        $mail->AddAddress(DEFAULT_EMAIL);
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $body = '<br/>'.$recipientName.' ('.$candidateId.') replied to your recent SMS at '.$datetime_entry.'<br>Sender Mobile:'.$senderMobile.'<br>Text Message :'.$response.'[recieved to :'.$recipientNumber.']';
        $mail->Body = $body;
        $mail->send();
        /*** send email end ***/
        if($mail){
            echo "SUCCESS";
        }else{
            echo "FAILURE";
        }
    }else{
        require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
        $mail = new PHPMailer();
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Username = DEFAULT_EMAIL;
        $mail->Password = DEFAULT_EMAIL_PASSWORD;
        $mail->SMTPSecure = "tls";
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host = "outlook.office365.com";
        $mail->LE = "\r\n";
        $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
        $subject = 'SMS REPLY Via MessageMedia - Not Recorded';
        $mail->AddAddress(DEFAULT_EMAIL);
        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $body = '<br/><br/>'.$recipientName.'('.$candidateId.') replied to your recent SMS at '.$datetime_entry.'<br>Sender Mobile:'.$senderMobile.'<br>Text :'.$response;
        $mail->Body = $body;
        $mail->send();
        /*** send email end ***/
        if($mail){
            echo "SUCCESS";
        }else{
            echo "FAILURE";
        }
    }
}
?>