<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';

$clientId = $_REQUEST['clientId'];
$status = $_REQUEST['status'];
$auditStatus = $_REQUEST['auditStatus'];
$chUser = $_SESSION['userSession'];
if(!empty($clientId)&&!empty($status)){
    echo updateClientStatus($mysqli,$clientId,$status,$chUser);
}elseif (!empty($clientId)&&!empty($auditStatus)){
    echo updateClientAuditStatus($mysqli,$clientId,$auditStatus,$chUser);
}
