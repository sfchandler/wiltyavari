<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canId = $_REQUEST['cid'];
$attempt = $_REQUEST['attempt'];
$_SESSION['sid'] = session_id();
$sessionId = $_SESSION['sid'];
$chkArray = $_REQUEST['chkArray'];
if($attempt==0){
	$del = $mysqli->prepare("DELETE FROM tmpsmslist WHERE candidateId != ? AND sessionId = ?") or die($mysqli->error);
	$del->bind_param("ss",$canId,$sessionId) or die($mysqli->error);
	$del->execute();
	$del->free_result();
}
if(!empty($chkArray)){
    foreach ($chkArray as $empId) {
        if(!validateSMSList($mysqli,$empId,$sessionId)){
            $stmt = $mysqli->prepare("SELECT candidateId, firstName, lastName, mobileNo FROM candidate WHERE candidateId = ?") or die($mysqli->error);
            $stmt->bind_param("s", $empId) or die($mysqli->error);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($candidateId, $firstName, $lastName, $mobileNo);
            $num_of_rows = $stmt->num_rows;
            $smsIns = $mysqli->prepare("INSERT INTO tmpsmslist(sessionid,candidateId,firstName,lastName,mobileNo)VALUES(?,?,?,?,?)") or die($mysqli->error);
            if ($num_of_rows > 0) {
                while ($stmt->fetch()) {
                    $smsIns->bind_param("sssss", $sessionId, $candidateId, $firstName, $lastName, $mobileNo) or die($mysqli->error);
                    $smsIns->execute();
                }
            }
        }
    }
    echo getTmpSMSList($mysqli, $sessionId);
}else {
    $consultantId = getConsultantId($mysqli, $_SESSION['userSession']);
    $smsList = $mysqli->prepare("SELECT candidateId FROM tmpsmslist WHERE candidateId = ? AND sessionId = ?") or die($mysqli->error);
    $smsList->bind_param("ss", $canId, $sessionId) or die($mysqli->error);
    $smsList->execute();
    $smsList->store_result();
    $smsList->fetch();
    $nrows = $smsList->num_rows;
    $smsList->free_result();
    if ($nrows > 0) {
        echo getTmpSMSList($mysqli, $sessionId);
    } else {
        $stmt = $mysqli->prepare("SELECT candidateId, firstName, lastName, mobileNo FROM candidate WHERE candidateId = ?") or die($mysqli->error);
        $stmt->bind_param("s", $canId) or die($mysqli->error);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($candidateId, $firstName, $lastName, $mobileNo);
        $num_of_rows = $stmt->num_rows;
        $smsIns = $mysqli->prepare("INSERT INTO tmpsmslist(sessionid,candidateId,firstName,lastName,mobileNo)VALUES(?,?,?,?,?)") or die($mysqli->error);
        if ($num_of_rows > 0) {
            while ($stmt->fetch()) {
                $smsIns->bind_param("sssss", $sessionId, $candidateId, $firstName, $lastName, $mobileNo) or die($mysqli->error);
                $smsIns->execute();
                $nrow = $smsIns->affected_rows;
                if ($nrow > 0) {
                    echo getTmpSMSList($mysqli, $sessionId);
                } else {
                    echo getTmpSMSList($mysqli, $sessionId);
                }
            }
        }
    }
}
?>