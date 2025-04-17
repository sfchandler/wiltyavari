<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
//$limitStart = '';
//$firstRow = '';
$limitStart = isset($_POST['limitStart']);
$limitCount = 20;
$firstRow = isset($_POST['firstRow']);
if(!empty($firstRow)){
    echo getSMSLogByCandidateIdUpdate($mysqli,$firstRow,$_POST['canId'],$_POST['smsStatus']);
}else{
    echo getSMSLogByCandidateIdAll($mysqli,$_POST['canId'],$_POST['smsStatus']);
}
/*if(isset($limitStart) || !empty($limitStart)) {
    echo getSMSLogByCandidateId($mysqli,$_POST['canId'],$_POST['smsStatus'],$limitStart,$limitCount);
}*/
?>