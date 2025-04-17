<?php
session_start();
/*if (isset($_SESSION['userSession'])) {

    //echo '0';
}else{
    // server should keep session data for AT LEAST 1 hour
    //ini_set('session.gc_maxlifetime', 3600);
    // each client should remember their session id for EXACTLY 1 hour
    //session_set_cookie_params(3600);
    //session_start(); // ready to go!
    echo '1';
}*/
echo ini_get('session.gc_maxlifetime');
if(time() - $_SESSION['login_time'] >= 1440){
    session_destroy(); // destroy session.
    //header("Location: logout.php");
    //die(); //
    //redirect if the page is inactive for 30 minutes
    echo '1';
}
else {
    $_SESSION['login_time'] = time();
    // update 'login_time' to the last time a page containing this code was accessed.
    echo '0';
}