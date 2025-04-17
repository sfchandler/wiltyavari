<!DOCTYPE html>
<html lang="en">
<head>
<!--<script src="./js/jquery-3.1.1.js"></script>
	<script type="text/javascript">
        
		$(function() {
            var time = $("#time");
			var t = 3; //18 minutes
			isVisibleTime = time.is(":visible");
			if(isVisibleTime == true){
				(function countDown(){
					if (t--) {
					   $('#time').text(t + ' s');
					   setTimeout(countDown, 1000);
					} else {
					   window.location.reload();
					}
				})();
			}
        });
    </script>-->
    </head>
<body><div id="time">1</div></body>
</html>
<?php 
//http://www.yourserver.com/inbound.php?to=61400111222&from=61400111999&message=Hello%20World&ref=abc123 
$to = $_GET["to"]; //The receiving mobile number 
$from = $_GET["from"]; //The sending mobile number 
$ref = $_GET["ref"]; // Your reference number, if provided when sending 
$smsref = $_GET["smsref"]; // SMS Broadcast reference number 
$status = $_GET["status"]; // Message status 
echo 'MSG'.$message = urldecode($_GET["message"]); //SMS content 


// You may wish to log this information in a database 

// Lets send an email with the message data 
$email_message = "Inbound SMS sent to $to.\nSent From: $from\nMessage: $message"; 
if(!empty($message)){
require("./includes/PHPMailer-master-old/PHPMailerAutoload.php");
	
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
	$subject = 'Inbound SMS';
	$mail->AddAddress('');
	$mail->Subject = $subject;
	$mail->IsHTML(true);
	$body = 'Inbound SMS SMSBroadcast, '.$message.' - > '.$email_message.'ref>'.$ref.'smsref>'.$smsref.'status>'.$status.'';
	$mail->Body = $body;
	if($mail){
		echo "SUCCESS";
	}else{
		echo "FAILURE";	
	}

}