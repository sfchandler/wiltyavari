<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");

date_default_timezone_set('Australia/Melbourne');

$user_name = $_POST['user_name'];
$id = $_POST['id'];
$v_code = $_POST['v_code'];
if(validateStaffVerificationCode($mysqli, $user_name,$v_code)){
    $_SESSION['session_id'] = base64_decode($id);
    $_SESSION['login_time'] = time();
    $_SESSION['token'] = md5(uniqid(mt_rand(), true));
    $_SESSION['staff_username']=base64_decode($user_name);
    header("location:staff_dashboard.php");
    exit;
}else{
    $msg = base64_encode("Invalid Verification code");
    header("Location:device_confirmations.php?user_name=$user_name&id=$id&error_msg=$msg");
    exit;
}
?>
