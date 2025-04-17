<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';

$invPath = $_POST['invPath'];
$invEmails = $_POST['invCheckboxes'];
$emails = explode(',',$invEmails);
$invoiceDate = $_POST['invoiceDate'];
$id = $_POST['id'];

try {
    updateInvoiceSentStatus($mysqli,$id);
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = "chandleraccounts@chandlerservices.com.au";
    $mail->Password = "ump@qbj*adm8PEJ7xaz";
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
    $mail->setFrom('chandleraccounts@chandlerservices.com.au', DOMAIN_NAME);
    $subject = 'Chandler Pacific Invoice for WE '.$invoiceDate;
    $mail->Subject = $subject;
    foreach ($emails as $em){
        $mail->AddAddress($em);
    }
    $mail->AddAttachment($_FILES['invTimesheet1']['tmp_name'],$_FILES['invTimesheet1']['name']);
    $mail->AddAttachment($_FILES['invTimesheet2']['tmp_name'],$_FILES['invTimesheet2']['name']);
    $mail->AddAttachment($_FILES['invTimesheet3']['tmp_name'],$_FILES['invTimesheet3']['name']);
    $mail->AddAttachment(substr($invPath,2));
    $mail->IsHTML(true);
    $body = 'Please find attached your Invoice and supporting timesheet for week ending '.$invoiceDate.'.
            <br><br><br><br><br>
            <i>Kind regards</i>
            <br>
            <i>Chandler Pacific Pty Ltd</i>
            ';
    $mail->Body = $body;
    $mail->send();
    if ($mail) {
        echo "SUCCESS";
    } else {
        echo "FAILURE";
    }
}catch (Exception $e){
    echo $e->getMessage();
}