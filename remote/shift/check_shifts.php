<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
date_default_timezone_set('Australia/Melbourne');

// include database and object files
include_once '../config/database.php';
include_once '../objects/shift.php';
include_once '../objects/candidate.php';
include_once '../objects/timeclock.php';
include_once '../objects/department.php';

try{
    $database =  new Database();
    $db = $database->getConnection();

}catch (PDOException $e){
    echo $e->getMessage();
}
$candidate = new Candidate($db);

//$data = json_decode(file_get_contents("php://input"));

$candidate->clockPin = urldecode($_POST['clockPin']);//$data->clockPin;

$stmt = $candidate->checkPIN();

$num = $stmt->rowCount();
$errorArr =  array();
if($num > 0){

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    // set values to object properties
    $candidate->firstName = $row['firstName'];
    $candidate->lastName = $row['lastName'];
    $candidate->candidateId = $row['candidateId'];
    $candidate->clockPin = $row['clockPin'];

    $shifts = new Shift($db);
    $shifts->candidateId = $row['candidateId'];
    $shifts->shiftDate = date('Y-m-d');
    /*$shifts->shiftStatus = 'CONFIRMED';*/
    $shiftData = $shifts->read();
    $numRows = $shiftData->rowCount();

    if($numRows > 0){
        $shifts_arr = array();

        while($row = $shiftData->fetch(PDO::FETCH_ASSOC)){
            //extract($rows);
            $shiftCheckInStatus = " ";
            $shiftCheckOutStatus = " ";
            $timeClock = new timeclock($db);
            $timeClock->shiftId = $row['shiftId'];

            try{
                $timeCkIn = $timeClock->getCheckInTime();
                $chkIn = $timeCkIn->fetch(PDO::FETCH_ASSOC);
                if($chkIn['checkIn'] == null){
                    $shiftCheckInStatus = "false";
                }else if($chkIn['checkIn'] == "00:00"){
                    $shiftCheckInStatus = "false";
                }else{
                    $shiftCheckInStatus = "true";
                }
            }catch (Exception $e){
                $e->getMessage();
                //$shiftCheckInStatus = $e->getMessage();
            }

            try{
                $timeCkOut = $timeClock->getCheckOutTime();
                $chkOut = $timeCkOut->fetch(PDO::FETCH_ASSOC);
                if($chkOut['checkOut'] == null && $shiftCheckInStatus == "true"){
                    $shiftCheckOutStatus = "false";
                }else if($chkOut['checkOut'] == "00:00" && $shiftCheckInStatus == "true"){
                    $shiftCheckOutStatus = "false";
                }else{
                    $shiftCheckOutStatus = "true";
                }
            }catch (Exception $e1){
                $e1->getMessage();
                //$shiftCheckOutStatus = $e1->getMessage();
            }

            $departments = new Department($db);
            $departments->clientId = $row['clientId'];
            $departments->stateId = $row['stateId'];
            $deptArr = $departments->getDepartmentList();

            $shift_item = array("shiftId"=> $row['shiftId'],
                "shiftDate"=> $row['shiftDate'],
                "tandaShiftId"=> $row['tandaShiftId'],
                "tandaTimesheetId"=> $row['tandaTimesheetId'],
                "shiftDate"=> $row['shiftDate'],
                "shiftDay"=> $row['shiftDay'],
                "clientId"=> $row['clientId'],
                "stateId"=> $row['stateId'],
                "departmentId"=> $row['departmentId'],
                "departmentList"=> $deptArr,
                "candidateId"=> $row['candidateId'],
                "shiftStart"=> $row['shiftStart'],
                "shiftEnd"=> $row['shiftEnd'],
                "workBreak"=> $row['workBreak'],
                "shiftNote"=> $row['shiftNote'],
                "shiftStatus"=> $row['shiftStatus'],
                "shiftSMSStatus"=> $row['shiftSMSStatus'],
                "consultantId"=> $row['consultantId'],
                "positionId"=> $row['positionId'],
                "timeSheetStatus"=> $row['timeSheetStatus'],
                "addressId"=> $row['addressId'],
                "shiftCheckInStatus"=> $shiftCheckInStatus,
                "shiftCheckOutStatus"=> $shiftCheckOutStatus,
                "employeeName"=>$candidate->firstName.' '.$candidate->lastName);
            array_push($shifts_arr,$shift_item);
        }
        echo json_encode($shifts_arr);
    }else{
        $errorArr = array("message"=>"SHIFTS NOT FOUND");
        echo json_encode($errorArr);
    }
}else{
    $errorArr = array("message"=>"INVALID PIN");
    echo json_encode($errorArr);
}


