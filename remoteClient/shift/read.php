<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 27/07/2018
 * Time: 5:22 PM
 */
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once 'remoteClient/config/database.php';
include_once 'remoteClient/objects/shift.php';

//instantiate database and shift object
/*$database =  new Database();
$db = $database->getConnection();*/
try{
    $database =  new Database();
    $db = $database->getConnection();

}catch (PDOException $e){
    echo $e->getMessage();
}
//intialize object
$shift = new Shift($db);
//query shifts

$stmt = $shift->read();
$num = $stmt->rowCount();

//check if more than 0 records found
if($num > 0){
    $shifts_arr = array();
    $shifts_arr['records']=array();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //extract row
        //make $row['name'] to $name
        extract($row);
        $shift_item = array("shiftId"=> $shiftId,
            "shiftDate"=> $shiftDate,
            "tandaShiftId" => $tandaShiftId,
            "tandaTimesheetId"=> $tandaTimesheetId,
            "shiftDate"=> $shiftDate,
            "shiftDay"=> $shiftDay,
            "clientId"=> $clientId,
            "stateId"=> $stateId,
            "departmentId"=> $departmentId,
            "candidateId"=> $candidateId,
            "shiftStart"=> $shiftStart,
            "shiftEnd"=> $shiftEnd,
            "workBreak"=> $workBreak,
            "shiftNote"=> $shiftNote,
            "shiftStatus"=> $shiftStatus,
            "shiftSMSStatus"=> $shiftSMSStatus,
            "consultantId"=> $consultantId,
            "positionId"=> $positionId,
            "timeSheetStatus"=> $timeSheetStatus,
            "addressId"=> $addressId);
        array_push($shifts_arr['records'],$shift_item);
    }
    echo json_encode($shifts_arr);
}else{
    echo json_encode(array("message"=>"No shifts found"));
}