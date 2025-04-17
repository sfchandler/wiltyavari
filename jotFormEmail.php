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
$consultantEmail = base64_decode($_REQUEST['conEmail']);
$reg_instructions = $_REQUEST['reg_instructions'];
$empMailBody = $_REQUEST['mailbody'].'<br><br>'.$reg_instructions;
$candidateId = base64_decode($_REQUEST['candidateId']);
$empEmail = getEmployeeEmail($mysqli,$candidateId);
if(!empty($empEmail)) {
    try {
        addQuestionnaire($mysqli,$candidateId);
        addSignature($mysqli,$candidateId);
        $mailStatus = generateMailNotification(DOMAIN_NAME.' online registration form', $consultantEmail, $empEmail, $empMailBody);
        if($mailStatus == 'SUCCESS'){
            updateRegPackSentTime($mysqli,$candidateId);
        }
        echo $mailStatus;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}else{
    echo 'N/A';
}

