<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*require_once 'googleLib/GoogleAuthenticator.php';*/

//require("includes/PHPMailer-master/PHPMailerAutoload.php");*/
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if($_POST['action']=='DeActivate'){
    $usrName = $_POST['usrName'];
    userActivateDeActivate($mysqli,0,$usrName);
    echo $userData = getUsers($mysqli);
}else if($_POST['action']=='Activate'){
    $usrName = $_POST['usrName'];
    userActivateDeActivate($mysqli,1,$usrName);
    echo $userData = getUsers($mysqli);
}else if($_POST['action']=='Delete'){
    $usrName = $_POST['usrName'];
    userDelete($mysqli,$usrName);
    echo $userData = getUsers($mysqli);
}else if($_POST['action']=='AddUser'){
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $loginType = $_POST['loginType'];
    /*$ga = new GoogleAuthenticator();
    $secret = $ga->createSecret();*/
    echo addNewSystemUser($mysqli,$userName,$password,$fullName,$email,$loginType,$mobile);
}else {
    echo $userData = getUsers($mysqli);
}
?>