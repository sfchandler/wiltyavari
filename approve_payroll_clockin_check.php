<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
$status = '';
$shiftArray = array();
$clientId = '';

/*foreach ($_POST['shiftId'] as $cnt => $shiftId) {
    if (!empty($_POST['checkTick'][$cnt])) {
        if (validateExistingCasualAttendance($mysqli, $_POST['shiftId'][$cnt])) {
            $status = payrollApproveAttendance($mysqli, $_POST['shiftId'][$cnt], $_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt],$_POST['payrollCheckInTime'][$cnt], $_POST['checkOutTime'][$cnt],$_POST['payrollCheckOutTime'][$cnt], $_POST['workBreak'][$cnt], calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]), 'N', 0, $_POST['position_id'][$cnt], $_POST['clientId'][$cnt],$_POST['comment'][$cnt]);
            $shiftArray[] = array('shiftId' => $_POST['shiftId'][$cnt], 'checkInTime' => $_POST['checkInTime'][$cnt], 'checkOutTime' => $_POST['checkOutTime'][$cnt], 'workBreak' => $_POST['workBreak'][$cnt], 'wrkhrs' => calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]));
            update_payroll_clock_in_out_log($mysqli,$_POST['shiftId'][$cnt],'check in '.$_POST['checkInTime'][$cnt].' check out '.$_POST['checkOutTime'][$cnt].' break '.$_POST['workBreak'][$cnt].' comment '.$_POST['comment'][$cnt].' added by '.$_SESSION['userSession']);
        } else {
            $status = payrollSaveCasualAttendance($mysqli, $_POST['shiftId'][$cnt], $_POST['clientId'][$cnt], $_POST['positionId'][$cnt], $_POST['candidateId'][$cnt], $_POST['shiftDate'][$cnt], $_POST['shiftDay'][$cnt], $_POST['checkInTime'][$cnt],$_POST['payrollCheckInTime'][$cnt], $_POST['checkOutTime'][$cnt],$_POST['payrollCheckOutTime'][$cnt], $_POST['workBreak'][$cnt], calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]), 'N', 0,$_POST['comment'][$cnt]);
            $shiftArray[] = array('shiftId' => $_POST['shiftId'][$cnt], 'checkInTime' => $_POST['checkInTime'][$cnt], 'checkOutTime' => $_POST['checkOutTime'][$cnt], 'workBreak' => $_POST['workBreak'][$cnt], 'wrkhrs' => calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]));
            update_payroll_clock_in_out_log($mysqli,$_POST['shiftId'][$cnt],'check in '.$_POST['checkInTime'][$cnt].' check out '.$_POST['checkOutTime'][$cnt].' break '.$_POST['workBreak'][$cnt].' comment '.$_POST['comment'][$cnt].' added by '.$_SESSION['userSession']);
        }
    }
    $clientId = $_POST['clientId'][$cnt];
}
echo $status;*/

?>