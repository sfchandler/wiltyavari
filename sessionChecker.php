<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 14/06/2017
 * Time: 12:12
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(!isset( $_SESSION['userSession'])|| time() - $_SESSION['login_time'] > 43200)//32400 = 9 hours//2 hour session expiration || time() - $_SESSION['login_time'] > 7200
{
    updateLoggedInTime($mysqli,$_SESSION['userSession'],date("Y-m-d H:i:s"),'LOGIN EXPIRED');
    //expired
    echo "-1";
    session_destroy();
}
else
{
    //not expired
    echo "1";
}
/*function auto_logout($field)
{
    $t = time();
    $t0 = $_SESSION[$field];
    $diff = $t - $t0;
    if ($diff > 1500 || !isset($t0))
    {
        return true;
    }
    else
    {
        $_SESSION[$field] = time();
    }
}
if(auto_logout("user_time"))
    {
        session_unset();
        session_destroy();
        location("login.php");
        exit;
    }
*/
?>