<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
$status = 'CLOSED';
$payrunId = 1;
$sql = $mysqli->prepare("SELECT DISTINCT candidateId FROM payrundetails WHERE payrunId = ? AND status = ? ORDER BY candidateId") or die($mysqli->error);
$sql->bind_param("is",$payrunId,$status) or die($mysqli->error);
$sql->execute();
$sql->bind_result($candidateId)or die($mysqli->error);
$sql->store_result();
while ($sql->fetch()){
    echo 'ID '.$candidateId.'<br>';
    try {
        echo notifyPayslipIssue(getEmployeeEmail($mysqli, $candidateId));
    }catch (Exception $e){
        echo $e->getMessage();
    }
}

/*$empEmail = 'swarnajithf@chandlerservices.com.au';
notifyPayslipIssue($empEmail);*/
function notifyPayslipIssue($empEmail)
{
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
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
    $mail->clearReplyTos();
    $mail->addReplyTo(DEFAULT_EMAIL, 'Reply-To');
    $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
    $subject = 'PLEASE IGNORE THE PREVIOUS PAYSLIP';//'PLEASE IGNORE THE PREVIOUS PAYSLIP AND REFER THE ATTACHED';
    $mail->AddAddress($empEmail);
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $temp_text = '<br/><br/>';
    $body = $temp_text . 'Good Afternoon,
<br/>
<br/> 
Please ignore the system generated pay slip email you have received through '.DOMAIN_NAME.'. This is due to a system error and we have rectified the issue.
<br/>
<br/>
Apologies for the inconvenience.
<br/>
<br/>
Kind regards
<br/>
'.DOMAIN_NAME.'
<br/>
<br/>
';
    $mail->Body = $body;
    $mail->send();

    if ($mail) {
        return $empEmail . ' Sent <br>';
    } else {
        return $empEmail . ' Failed <br>';
    }
}