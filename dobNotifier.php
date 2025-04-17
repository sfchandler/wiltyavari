<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
ini_set('max_execution_time', 0);
set_time_limit(0);
date_default_timezone_set('Australia/Melbourne');

try {
    $dobInfo = dateOfBirthNotifier($mysqli);
    $txt = '<p style="font-size: 11pt;font-family: Montserrat, Calibri, Candara, Arial, sans-serif"> <b>Following employees are celebrating their birthdays today </b><br><br>';
    foreach ($dobInfo as $dobIn) {
        $txt = $txt . '<div style="font-size: 11pt;font-family: Montserrat, Calibri, Candara, Arial, sans-serif; text-indent: 10px;">' . $dobIn['candidateId'] . ' - ' . $dobIn['firstName'] . ' ' . $dobIn['lastName'] . ' age of '.getCandidateAge($mysqli,$dobIn['candidateId']).'</div><br>';
    }
    $txt = $txt . '</p>';

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
    $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
    $mail->AddAddress('outapay@outapay.com');
    $mail->AddEmbeddedImage("../img/chandler-logo-mail.jpg", "chandlerLogo", "chandler-logo-mail.jpg");
    /*if (!empty($cc)) {
        $mail->AddCC($cc);
    }*/
    //if (!empty($bcc)) {
    //}
    $mail->Subject = 'Employee Birthday 🎂 Notifier ';
    $mail->IsHTML(true);
    $mail->Body = $txt;
    $mail->AddAttachment('');
    $mail->AddAttachment('');
    $mail->send();
    if ($mail) {
        return "MAILSENT";
    } else {
        return "FAILURE";
    }
}catch (Exception $e){
    $e->getMessage();
}