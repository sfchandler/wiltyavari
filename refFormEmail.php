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
$candidateId = $_REQUEST['candidateId'];
$refFormEmail = $_REQUEST['refFormEmail'];
if(!empty($refFormEmail)) {
    try {
        echo generateMailNotification(DOMAIN_NAME.' online reference check form',$consultantEmail, $refFormEmail, $empMailBody);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}else{
    echo 'N/A';
}

