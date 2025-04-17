<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

$usr = $_SESSION['userSession'];
$exPassword = mysqli_real_escape_string($mysqli,$_POST['exPassword']);
$newPassword = mysqli_real_escape_string($mysqli,$_POST['newPassword']);
$confPassword = mysqli_real_escape_string($mysqli,$_POST['confPassword']);
//echo $_POST['exPassword'].$_POST['newPassword'].$_POST['confPassword'].$usr.'<br/>';

if(isset($exPassword)&& isset($newPassword)&& isset($confPassword)){
    if(checkExistingPassword($mysqli,$usr,$exPassword) == '1'){
        if($newPassword == $confPassword){
            echo changeChandlerUserPassword($mysqli,$usr,$newPassword);
        }else{
            echo 'New Passwords does not match';
        }
    }else{
        echo 'Existing Password is incorrect';
    }
}else{
    echo 'Please fill all the fields';
}
?>