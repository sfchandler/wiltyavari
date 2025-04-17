<?php
	session_start();
    require_once("includes/db_conn.php");
    require_once("includes/functions.php");
	//delete all sms list held in temporary
    deleteAllTmpSMSList($mysqli,$_SESSION['sid']);

	/*if(isset($_GET['csrf']) && $_GET['csrf'] == $_SESSION['token']){*/
        $sessionId = getLoggedInSessionId($mysqli,$_SESSION['userSession']);
        updateVerificationCode($mysqli,$_SESSION['user_id'],'');
	    updateLoggedInTime($mysqli,$_SESSION['user_id'],$_SESSION['userSession'],$sessionId,date("Y-m-d H:i:s"),'LOGGED OUT');
        //remove PHPSESSID from browser
        $quote = "";
        $path = "/";
        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), $quote, time()-3600, $path);

        //clear session from globals
        $_SESSION = array();
		/*session_unset($_SESSION['searchTxt']);
		session_unset($_SESSION['subjectSearchTxt']);
		session_unset($_SESSION['fromSearchTxt']);
		session_unset();*/
        //clear session from disk
		session_destroy();
		$msg = base64_encode("You have successfully logged out");
		header("Location:login.php?error_msg=$msg");
	/*}*/
    /*else{
		$_SESSION = array();
		session_unset();
		session_destroy();
		$msg = base64_encode("You have successfully logged out");
		header("Location:login.php?error_msg=$msg");
	}*/
?>