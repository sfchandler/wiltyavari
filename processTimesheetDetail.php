<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 1/05/2018
 * Time: 4:50 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$candidateId = $_REQUEST['candidateId'];
$transCode = $_REQUEST['transCode'];
$jobCode = strtoupper($_REQUEST['jobCode']);
$weekendingDate = $_REQUEST['weekendingDate'];
$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
$transCodeAmount = $_REQUEST['transCodeAmount'];
if($_REQUEST['action'] == 'Remove'){
    removeTimesheetDetail($mysqli,$transCode,$candidateId,$jobCode,$weekendingDate);
    echo getTimesheetDetail($mysqli,$candidateId,$jobCode,$weekendingDate);
}else if($_REQUEST['action'] == 'Add'){
    if(!empty($candidateId)&&!empty($transCode)&&!empty($jobCode)&&!empty($weekendingDate)&&!empty($clientId)&&!empty($positionId)){
        $status = saveTimesheetDetail($mysqli,$candidateId,$transCode,$jobCode,$weekendingDate,$clientId,$positionId,$transCodeAmount);
        if($status == 'inserted'){
            echo getTimesheetDetail($mysqli,$candidateId,$jobCode,$weekendingDate);
        }else if($status == 'error'){
            echo '<tr><td colspan="3">'.$status.'</td></tr>';
        }else if($status == 'exists'){
            echo getTimesheetDetail($mysqli,$candidateId,$jobCode,$weekendingDate);
        }else{
            echo getTimesheetDetail($mysqli,$candidateId,$jobCode,$weekendingDate);
        }
    }else{
        echo '<tr><td colspan="3">Please select all required Information</td></tr>';
    }
}else if($_REQUEST['action'] == 'Get'){
    echo getTimesheetDetail($mysqli,$candidateId,$jobCode,$weekendingDate);
}
