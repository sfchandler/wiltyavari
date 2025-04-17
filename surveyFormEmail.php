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
$consultantEmail = $_REQUEST['conEmail'];
$empMailBody = $_REQUEST['mailbody'].'<br><br>';
$candidateId = base64_decode($_REQUEST['candidateId']);
$empEmail = getEmployeeEmail($mysqli,$candidateId);
if(!empty($empEmail)) {
    try {
        $status = generateMailNotification(DOMAIN_NAME.' Customer Survey Request',$consultantEmail, $empEmail, $empMailBody);
        if($status == 'SUCCESS'){
           updateCustomerSurveySent($mysqli,$candidateId,date('Y-m-d H:i:s'));
           updateCustomerSurveyStatus($mysqli,$candidateId,1);
        }
        echo $status;
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}else{
    echo 'N/A';
}

