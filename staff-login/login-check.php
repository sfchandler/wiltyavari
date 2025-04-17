<?php
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
$username = mysqli_real_escape_string($mysqli,$_POST['username']);
$action = mysqli_real_escape_string($mysqli,$_POST['action']);
$shift_id = mysqli_real_escape_string($mysqli,$_POST['shift_id']);
$client_id = mysqli_real_escape_string($mysqli,$_POST['client_id']);
$shift_date = mysqli_real_escape_string($mysqli,$_POST['shift_date']);
$pwd = mysqli_real_escape_string($mysqli,$_POST['password']);
$msg = base64_encode("Submit not set");
header("Location:index.php");
if(isset($_POST['submit'])){
    $sql = $mysqli->prepare("SELECT password FROM candidate WHERE username = ?") or die($mysqli->error);
    $sql->bind_param("s",$username)or die($mysqli->error);
    $sql->execute();
    $password = $sql->get_result()->fetch_object()->password;
    if(password_verify($pwd,$password)){
        if($action == 'COVIDCHECK'){
            session_start();
            $_SESSION['staffSession'] = $username;
            header("Location:covidcheck.php?shift_id=".$shift_id."&shift_date=".$shift_date."&client_id=".$client_id);
        }else {
            session_start();
            $_SESSION['staffSession'] = $username;
            header("Location:dashboard.php");
        }
    }else{
        $msg = base64_encode("Invalid username or password");
        header("Location:index.php?msg=$msg");
    }
    /*$msg = base64_encode("Invalid username or password");
    header("Location:index.php?msg=$msg");*/
}else{
    $msg = base64_encode("Submit not set");
    header("Location:index.php?msg=$msg");
}
