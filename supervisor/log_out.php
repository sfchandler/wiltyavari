<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");

    updateSupervisorLoggedInTime($mysqli,$_SESSION['usrSession'],$_SESSION['supervisorId'],date("Y-m-d H:i:s"),'LOGGED OUT');
    //remove PHPSESSID from browser
    if (isset($_COOKIE[session_name()]))
        setcookie(session_name(), “”, time()-3600, “/” );
    session_unset();
    session_destroy();
    $msg = base64_encode("You have successfully logged out");
    header("Location:index.php?error_msg=$msg");
?>