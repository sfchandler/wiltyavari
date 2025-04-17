<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*include_once __DIR__ . '/includes/GoogleAuthenticator-2.x/src/FixedBitNotation.php';
include_once __DIR__ . '/includes/GoogleAuthenticator-2.x/src/GoogleAuthenticatorInterface.php';
include_once __DIR__ . '/includes/GoogleAuthenticator-2.x/src/GoogleAuthenticator.php';*/
date_default_timezone_set('Australia/Melbourne');
/*$googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
$code = $_POST['code'];
if ($code) {
    $secret = $_SESSION['secret'];
    $googleAuth->getCode($secret);
    if ($googleAuth->checkCode($secret, $code, 4)) {
        $_SESSION['secret'] = $code;
        updateLoggedInTime($mysqli,$user_id,$user_name,$id,date("Y-m-d H:i:s"),'LOGGED IN');
        header("location:main.php");
        exit;
    } else {
        $msg = base64_encode("Invalid OTP code");
        header("location:device_confirmations.php?error_msg=$msg");
        exit;
    }
}*/
$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$id = $_POST['id'];
$v_code = $_POST['v_code'];
$type_login = base64_decode($_POST['typeLogin']);
$accountName = base64_decode($_POST['accName']);
if(validateVerificationCode($mysqli, $user_id,$v_code)){
    if($type_login=="CONSULTANT"){
        $_SESSION['accountName']=$accountName;
    }else if($type_login=="ALLOCATIONS"){
        $_SESSION['accountName']=$accountName;
    }else if($type_login=="ACCOUNTS"){
        $_SESSION['accountName']=$accountName;
    }else if($type_login=="ADMIN"){
        $_SESSION['accountName']=$accountName;
    }
    $_SESSION['session_id'] = base64_decode($id);
    $_SESSION['user_id'] = base64_decode($user_id);
    $_SESSION['userType'] = $type_login;
    $_SESSION['login_time'] = time();
    $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    $_SESSION['userSession']=base64_decode($user_name);
    updateLoggedInTime($mysqli,base64_decode($user_id),base64_decode($user_name),base64_decode($id),date("Y-m-d H:i:s"),'LOGGED IN');
    header("location:main.php");
    exit;
}else{
    $msg = base64_encode("Invalid Verification code");
    header("Location:device_confirmations.php?user_id=$user_id&user_name=$user_name&id=$id&error_msg=$msg");
    exit;
}
?>
