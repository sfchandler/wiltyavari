<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$action = $_REQUEST['action'];
$taxEmail = $_REQUEST['email'];
$consultantEmail = $_REQUEST['consultantEmail'];
$taxMailBody = $_REQUEST['mailbody'];
if($action != 'sendMail') {
    $messageId = htmlentities($_REQUEST['messageid']);
    $candidateMailFrom = retrieveCandidateName($mysqli, $messageId, $_SESSION['accountName']);
    $str = explode('via', $candidateMailFrom);
    $fullName = explode(' ', $str[0]);
    $firstName = trim($fullName[0]);
    $lastName = $fullName[1] . ' ' . $fullName[2];
    $msgBody = retrieveCandidateMsgBody($mysqli, $messageId, $_SESSION['accountName']);
    //$msgBody = retrieveCandidateEmailContent($mysqli, $messageId, $_SESSION['accountName']);
    $emailAddress = get_string_between($msgBody, 'mailto:', '&quot;');
    $phoneNumber = trim(get_string_between($msgBody, 'Phone\r\n                                                        &lt;/p&gt;\r\n                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;\r\n                                                            ', '\r\n'));
    if(!empty($emailAddress)){
        echo $emailAddress;
    }else{
        echo 'N/A';
    }
}else{
    if(!empty($taxEmail)) {
        try {
            echo generateMailNotification(DOMAIN_NAME.'- Tax Form to be submitted',$consultantEmail, $taxEmail, $taxMailBody);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }else{
        echo 'N/A';
    }
}
