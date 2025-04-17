<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$today = date('Y-m-d');
$sql = $mysqli->prepare("SELECT shiftId,shiftStart FROM shift WHERE shiftDate = ?") or die($mysqli->error);
$sql->bind_param("s",$today)or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($shiftId,$shiftStart)or die($mysqli->error);
while($sql->fetch()){
    $checkIn = getTimeclockCheckInForShift($mysqli,$shiftId);
    //echo 'SHIFTID -> '.$shiftId.'start'.$shiftStart.'  --> '.$checkIn.'<br>';
    notifyNoCheckIn($mysqli,$today,$shiftId,$shiftStart,$checkIn);
    //usleep(3000000);//3 seconds
}

function getTimeclockCheckInForShift($mysqli,$shiftId){
    $sql = $mysqli->prepare("SELECT checkIn FROM timeclock WHERE shiftId = ?") or die($mysqli->error);
    $sql->bind_param("i",$shiftId)or die($mysqli->error);
    $sql->execute();
    $checkIn = $sql->get_result()->fetch_object()->checkIn;
    return $checkIn;
}
function notifyNoCheckIn($mysqli,$shiftDate,$shiftId,$shiftStart,$checkIn){
    $date = $shiftDate.$shiftStart.':00';
    $currentDate = strtotime($date);
    $futureDate = $currentDate+(60*5);
    $futureFormatDate = date("Y-m-d H:i:s", $futureDate);
   // if($futureFormatDate == date("Y-m-d H:i:s")) {
        if (!empty($checkIn)) {
            $checkInFormatDate = $shiftDate . $checkIn . ':00';
            $checkInDate = strtotime($checkInFormatDate);
            if ($futureFormatDate >= $checkInDate) {
                echo ' Date ' . $date . ' Future Date ' . $futureFormatDate . '  checkInDate  ' . $checkInDate . '<br>';
            } else {
                echo 'Checked In early' . '<br>';
            }
        } else {
                echo 'Not Checked In' . '<br>';
                try {
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
        }
   // }
}