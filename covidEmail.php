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
$empMailBody = $_REQUEST['mailbody'];
$candidateId = $_REQUEST['candidateId'];
$empEmail = getEmployeeEmail($mysqli,$candidateId);
if(!empty($empEmail)) {
    try {
        echo generateMailNotification(DOMAIN_NAME.' Casual Covid Policy to be submitted',$consultantEmail, $empEmail, $empMailBody);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}else{
    echo 'N/A';
}

