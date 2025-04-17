<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 14/09/2018
 * Time: 11:49 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$candidateId = $_POST['candidateId'];
$reversalDate = $_POST['reversalDate'];
$reverseInfo = $_POST['reverseInfo'];
$reverseData = explode('|',$reverseInfo);
$weekendingDate = $reverseData[1];
$payRunId = $reverseData[0];
$payReversalDate = date('Y-m-d H:i:s');
$reversedBy = $_SESSION['userSession'];
if(isset($weekendingDate) && isset($payRunId) && isset($candidateId)){
    echo processPayReversal($mysqli,$weekendingDate,$payRunId,$candidateId,$payReversalDate,$reversedBy);
}else{
    echo 'Please select all the required fields';
}