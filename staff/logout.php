<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
updateStaffVerificationCode($mysqli,$_SESSION['staff_username'],'');
$quote = "";
$path = "/";
if (isset($_COOKIE[session_name()]))
    setcookie(session_name(), $quote, time()-3600, $path);

$_SESSION = array();
session_destroy();
$msg = base64_encode("You have successfully logged out");
header("Location:login.php?error_msg=$msg");

?>