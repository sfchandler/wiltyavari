<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 26/10/2017
 * Time: 12:49 PM
 */

require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
ini_set('display_errors', true); // set to false in production
error_reporting(E_ALL);
$username = mysqli_real_escape_string($mysqli,$_POST['username']);
//$usrPassword = md5($_POST['password']);
$usrPassword = $_POST['password'];

$login = $_POST['loginBtn'];
$user_type = 1;
$password = '';
if(isset($login)){
    $res = $mysqli->prepare("SELECT candidateId,candidate_no, email, password,type FROM candidate WHERE email = ? AND type = ? LIMIT 1") or die($mysqli->error);
    $res->bind_param("si",$username,$user_type);
    $res->execute();
    $res->bind_result($candidateId,$candidate_no,$email,$password,$type);
    $res->store_result();
    $num_of_rows = $res->num_rows;
    if($res->num_rows == 1){
        session_start();
        while ($res->fetch()) {
            if (password_verify($usrPassword,$password)) {
                if($type == 1) {
                    $_SESSION['usrSession'] = $email;
                    $_SESSION['user_type'] = 'SUPERVISOR';
                    $_SESSION['supervisorNo'] = $candidate_no;
                    $_SESSION['supervisorId'] = $candidateId;
                    $_SESSION['loginTime'] = time();
                    updateSupervisorLoggedInTime($mysqli, $email,$candidateId, date("Y-m-d H:i:s"), 'LOGGED IN');
                    header("Location: clockInList.php?error_msg=$msg");
                }
            }else{
                $msg = base64_encode("Invalid username or password");
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
