<?php
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
ini_set('display_errors', true);
error_reporting(E_ALL);

$username = mysqli_real_escape_string($mysqli,$_POST['username']);
//$usrPassword = md5($_POST['password']);
$usrPassword = $_POST['password'];
$login = $_POST['loginBtn'];
$user_type = 1;
$password = '';
if(isset($login)){
    $res = $mysqli->prepare("SELECT supervisorId,supervisorName,email,password,clientId,deptId FROM supervisor WHERE email = ? LIMIT 1") or die($mysqli->error);
    $res->bind_param("s",$username);
    $res->execute();
    $res->bind_result($supervisorId,$supervisorName,$email,$password,$clientId,$deptId);
    $res->store_result();
    $num_of_rows = $res->num_rows;
    echo $num_of_rows;
    if($res->num_rows == 1){
        session_start();
        while ($res->fetch()) {
            if (password_verify($usrPassword,$password)) {
                    $_SESSION['usrSession'] = $email;
                    $_SESSION['user_type'] = 'SUPERVISOR';
                    $_SESSION['supervisorName'] = $supervisorName;
                    $_SESSION['supervisorId'] = $supervisorId;
                    $_SESSION['supervisorClient'] = $clientId;
                    $_SESSION['supervisorDepartment'] = $deptId;
                    $_SESSION['loginTime'] = time();
                    updateSupervisorLoggedInTime($mysqli, $email,$supervisorId, date("Y-m-d H:i:s"), 'LOGGED IN');
                    $msg ='logged in';
                    header("Location: clockInList.php?error_msg=$msg");
            }else{
                $msg = base64_encode("Invalid username or password...");
                header("Location: clockInList.php?error_msg=$msg");
            }
        }
    }else{
        $msg = base64_encode("Invalid username or password!");
        header("Location: clockInList.php?error_msg=$msg");
    }
}else{
    $msg = base64_encode("Error - Validating");
    header("Location: clockInList.php?error_msg=$msg");
}

?>
