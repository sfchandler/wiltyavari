<?php
	session_start();
        //remove PHPSESSID from browser
        $quote = "";
        $path = "/";
        if (isset($_COOKIE[session_name()]))
            setcookie(session_name(), $quote, time()-3600, $path);

        //clear session from globals
        $_SESSION = array();
		session_unset();
        //clear session from disk
		session_destroy();
		$msg = base64_encode("You have successfully logged out");
		header("Location:index.php?msg=$msg");
?>