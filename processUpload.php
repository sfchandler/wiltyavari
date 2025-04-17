<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$fN = str_replace(' ','',trim($_POST['fN']));
$lN = str_replace(' ','',trim($_POST['lN']));
$fullName = $fN.' '.$lN;
$em = str_replace(' ','',trim($_POST['em']));
$ph = str_replace(' ','',trim($_POST['ph']));
$rc = str_replace(' ','',trim($_POST['rc']));
$autoId = $_POST['autoId'];
$jbId = $_POST['jbId'];
$reason_for_suitability = trim($_POST['reason_for_suitability']);
$ph_screen_time = trim($_POST['ph_screen_time']);
$msgId = $_POST['msgId'];
$consultantId = $_POST['consId'];
$msg = '';
if(!empty($fN) && !empty($lN) && !empty($em) && !empty($ph)){
	$canId = '';
    $response = candidateProfileCreationOnScreening($mysqli,$canId,$msgId,$fN,$lN,$fullName,$ph,$em,$rc,$consultantId,$autoId,$jbId,$reason_for_suitability,$ph_screen_time,$_SESSION['accountName'],$_SESSION['userSession']);
    $empId = base64_encode($response);
    $firstName = base64_encode($fN);
    $lastName = base64_encode($lN);
    $email = base64_encode($em);
    $phone = base64_encode($ph);
    if(!empty($response)){
        $msg = base64_encode('Profile created');
        header("Location: candidateMain.php?canId=$empId&fname=$firstName&lname=$firstName&eml=$email&mbl=$phone&error_msg=$msg");
    }else{
        $msg = "Error Uploading - A profile may exist";
        header("Location: candidateReview.php?fname=$firstName&lname=$lastName&eml=$email&mbl=$phone&error_msg=$msg");
    }
}else{
	$msg = "Error Uploading - parameters empty";
	header("Location: candidateReview.php?error_msg=$msg");
}
?>