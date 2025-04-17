<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/08/2018
 * Time: 4:42 PM
 */

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
date_default_timezone_set('Australia/Melbourne');

// include database and object files
include_once 'remoteClient/config/database.php';
include_once 'remoteClient/config/functions.php';
include_once 'remoteClient/objects/shift.php';
include_once 'remoteClient/objects/candidate.php';
include_once 'remoteClient/objects/jobcode.php';
include_once 'remoteClient/objects/timeclock.php';
require("../../includes/PHPMailer-master/PHPMailerAutoload.php");

try{
    $database =  new Database();
    $db = $database->getConnection();
}catch (PDOException $e){
    echo $e->getMessage();
}

$action = urldecode($_POST['action']);
$selectedDeptId = urldecode($_POST['selectedDeptId']);
$selectedTransport = urldecode($_POST['selectedTransport']);
$processStatus = array();

if($action == 'CHECK IN') {
    $shift = new Shift($db);
    $shift->shiftId = urldecode($_POST['shiftId']);
    $stmt = $shift->getShift();
    $num = $stmt->rowCount();
    if ($num == 1) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

             $timeclock = new Timeclock($db);
             $timeclock->shiftId = $shift->shiftId;
             $timeclock->candidateId = $row['candidateId'];
             $timeclock->shiftDate = $row['shiftDate'];
             $timeclock->shiftDay = $row['shiftDay'];
             $timeclock->clientId = $row['clientId'];
             $timeclock->positionId = $row['positionId'];
             $timeclock->deptId = $selectedDeptId;
             $timeclock->transport = $selectedTransport;

             $jobcode = new Jobcode($db);
             $jobcode->clientId = $row['clientId'];
             $jobcode->positionId = $row['positionId'];

             $jobCodeResult = $jobcode->getJobCode();
             $jb = $jobCodeResult->fetch(PDO::FETCH_ASSOC);
             $timeclock->jobCode = $jb['jobCode'];

             $timeclock->checkIn = date('H:i');
             $timeclock->checkOut = '00:00';
             $timeclock->workBreak = $row['workBreak'];
             $timeclock->wrkhrs = calculateHoursWorked($timeclock->shiftDate, $timeclock->checkIn, $timeclock->checkOut, $timeclock->workBreak);

             $candidate = new Candidate($db);
             $candidate->candidateId = $row['candidateId'];
             $supervicerIdResult = $candidate->getSupervisorId();
             $supId = $supervicerIdResult->fetch(PDO::FETCH_ASSOC);
             $timeclock->supervicerId = $supId['supervicerId'];

             $supervisor = new Candidate($db);
             $supervisor->candidate_no = $timeclock->supervicerId;

             $supSrResult = $supervisor->getSupervisor();
             $supSr = $supSrResult->fetch(PDO::FETCH_ASSOC);
             $timeclock->supervisor = $supSr['candidateId'];
             $mbody = $timeclock->shiftId.$row['candidateId'].' -- supervisor'.$timeclock->supervicerId.' - '.$timeclock->supervisor.' - '.date('Y-m-d H:i:s');
             genRemoteMailNotification('supervisor test remote client firebase','swarnajithf@chandlerservices.com.au',$mbody);
             $ins = $timeclock->saveCheckIn();
             if($ins){
                 $errorArr = array("message"=>"CHECK IN SUCCESSFUL");
                 echo json_encode($errorArr);
             }else{
                 $errorArr = array("message"=>"ERROR CHECK IN - CONTACT SUPPORT");
                 echo json_encode($errorArr);
             }
        }
    }else {
        array_push($processStatus,$action.'Cannot Find shift'.$_POST['shiftId']);
        echo json_encode($processStatus);
    }
}else if($action == 'CHECK OUT'){
    $timeclock = new timeclock($db);
    $timeclock->shiftId = urldecode($_POST['shiftId']);
    $stmt = $timeclock->getTimeClock();
    $num = $stmt->rowCount();
    if ($num == 1) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $timeclock->shiftDate = $row['shiftDate'];
            $timeclock->checkIn = $row['checkIn'];
            $timeclock->workBreak = $row['workBreak'];
            $timeclock->checkOut = date('H:i');
            $timeclock->wrkhrs = calculateHoursWorked($timeclock->shiftDate, $timeclock->checkIn, $timeclock->checkOut, $timeclock->workBreak);
            $timeclock->supervisorCheck = 'Y';
            $up = $timeclock->updateCheckOut();
            if($up){
                $errorArr = array("message"=>"CHECK OUT SUCCESSFUL");
                echo json_encode($errorArr);
            }else{
                $errorArr = array("message"=>"ERROR CHECKOUT - CONTACT SUPPORT");
                echo json_encode($errorArr);
            }
        }
    }

}

