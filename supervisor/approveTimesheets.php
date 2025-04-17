<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$status = '';
if(isset($_SESSION['supervisorId'])) {
    $shiftArray = array();
    $clientId = '';
    foreach ($_POST['shiftId'] as $cnt => $shiftId) {
        if (!empty($_POST['checkTick'][$cnt])) {
            /*$status = $_POST['shiftId'][$cnt].$_POST['clientId'][$cnt].$_POST['positionId'][$cnt].$_POST['candidateId'][$cnt].$_POST['shiftDate'][$cnt].$_POST['shiftDay'][$cnt].$_POST['checkInTime'][$cnt].$_POST['checkOutTime'][$cnt].$_POST['workBreak'][$cnt].$_POST['wrkhrs'][$cnt].'Y'.$_POST['supervicerId'][$cnt].'>>>>'.$_SESSION['supervisorId'].date('Y-m-d H:i:s');
            break;*/
            //$wrkhrs = calculateHoursWorked($_POST['shiftDate'][$cnt],$_POST['checkInTime'][$cnt],$_POST['checkOutTime'][$cnt],$_POST['workBreak'][$cnt]);
            if (validateExistingCasualAttendance($mysqli, $_POST['shiftId'][$cnt])) {
                $status = approveAttendance($mysqli, $_POST['shiftId'][$cnt],$_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt],$_POST['workBreak'][$cnt], calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]), 'Y', $_SESSION['supervisorId'],$_POST['position_id'][$cnt],$_POST['clientId'][$cnt],$_POST['comment'][$cnt]);
                $shiftArray[] = array('shiftId' => $_POST['shiftId'][$cnt], 'checkInTime' => $_POST['checkInTime'][$cnt], 'checkOutTime' => $_POST['checkOutTime'][$cnt], 'workBreak' => $_POST['workBreak'][$cnt], 'wrkhrs' => calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]));//$_POST['wrkhrs'][$cnt]
            }else{
                $status = saveCasualAttendance($mysqli,$_POST['shiftId'][$cnt],$_POST['clientId'][$cnt],$_POST['positionId'][$cnt],$_POST['candidateId'][$cnt],$_POST['shiftDate'][$cnt],$_POST['shiftDay'][$cnt],$_POST['checkInTime'][$cnt],$_POST['checkOutTime'][$cnt],$_POST['workBreak'][$cnt],calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]),'Y',$_SESSION['supervisorId'],$_POST['comment'][$cnt]);
                $shiftArray[] = array('shiftId' => $_POST['shiftId'][$cnt], 'checkInTime' => $_POST['checkInTime'][$cnt], 'checkOutTime' => $_POST['checkOutTime'][$cnt], 'workBreak' => $_POST['workBreak'][$cnt], 'wrkhrs' => calculateHoursWorked($_POST['shiftDate'][$cnt], $_POST['checkInTime'][$cnt], $_POST['checkOutTime'][$cnt], $_POST['workBreak'][$cnt]));//$_POST['wrkhrs'][$cnt]
            }
        }
        $clientId = $_POST['clientId'][$cnt];
    }
    if(($status == 'updated')||($status == 'inserted')){
        try{
            $client = getClientNameByClientId($mysqli,$clientId);
            $mailBody ='<span style="font-family:Arial, Verdana, Geneva, sans-serif; font-size:11pt;"> To Accounts Division, <br><br>'.getSupervisorNameById($mysqli,$_SESSION['supervisorId']).' supervisor has confirmed work timesheet at '.date('d-m-Y H:i:s').'</span>';
            $mailSubject = DOMAIN_NAME.' - Supervisor Timesheet Confirmations - '.$client;
            $mailFrom = DEFAULT_EMAIL;
            $fromName = 'Chandler Services';
            $supervisorLoginInfo = getSupervisorLoginInfoById($mysqli,$_SESSION['supervisorId']);
            generateClockInNotification(ACCOUNTS_EMAIL,getSupervisorNameById($mysqli,$_SESSION['supervisorId']),$supervisorLoginInfo,$client);
        }catch (Exception $e1){
            $e1->getMessage();
        }
        try{
            foreach ($shiftArray as $data){
                $canId = getCandidateIdByShiftId($mysqli,$data['shiftId']);
                $employeeName = getCandidateFullName($mysqli,$canId);
                $employeeEmail = getEmployeeEmail($mysqli,$canId);
                $shiftDate = getShiftDateByShiftId($mysqli,$data['shiftId']);
            }
        }catch (Exception $e2){
            $e2->getMessage();
        }
    }
}else{
    $status = 'login';
}
echo $status;
?>