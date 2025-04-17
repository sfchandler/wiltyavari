<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$shDate = $_REQUEST['shDate'];
$shDay = $_REQUEST['shDay'];
$clid = $_REQUEST['clid'];
$stid = $_REQUEST['stid'];
$did = $_REQUEST['did'];
$canid = $_REQUEST['canid'];
$StartTime = $_REQUEST['shiftStart'];
$EndTime = $_REQUEST['shiftEnd'];
$workBreak = $_REQUEST['workBreak'];
$note = $_REQUEST['note'];
$shiftCopy = $_REQUEST['shiftCopy'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$dateRange = $_REQUEST['dateRange'];
$positionId = $_REQUEST['positionid'];
$shStatus = $_REQUEST['shStatus'];
$addressId = $_REQUEST['addressId'];
if(($_REQUEST['shiftCallStatus'] != 'None') && !empty($_REQUEST['shiftCallStatus']) ) {
    $shStatus = $_REQUEST['shiftCallStatus'];
}elseif($_REQUEST['shiftCallStatus'] == 'None'){
    $shStatus = 'OPEN';
}
$bulkCanId = $_REQUEST['bulkCanId'];
$copyRoster = $_REQUEST['copyRoster'];
$consultantId = getConsultantId($mysqli, $_SESSION['userSession']);
if(!empty($bulkCanId)){
    if(is_string($bulkCanId)){
        $bulkCandidates = explode(',',$bulkCanId);
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );
        foreach ($bulkCandidates as $emp){
            foreach($period as $key=>$value){
                $shDate = $value->format('Y-m-d');
                $shDay = dayOfWeek($shDate);
                $shiftStatus = saveAndDisplayShift($mysqli, $shDate, $shDay, $clid, $stid, $did, $emp, $StartTime, $EndTime, $workBreak, $note, $shiftCopy, $startDate, $endDate, $dateRange, $positionId, $shStatus, $addressId,$consultantId,$_SESSION['userSession']);
                /*$dataArray = json_decode($shiftStatus, true);
                foreach ($dataArray as $item) {
                    $insertId = $item['insertId'];
                    saveShift($mysqli, $insertId);
                }*/
            }
        }
        $msgArray[] = array('status' => 'bulkShiftAdded');
        echo json_encode($msgArray);
    }else{
        $msgArray[] = array('status' => 'employeeID not a string');
        echo json_encode($msgArray);
    }
}elseif($copyRoster == 'COPYROSTER'){
    $clientId = $_REQUEST['clientId'];
    $stateId = $_REQUEST['stateId'];
    $deptId = $_REQUEST['deptId'];
    $positionId = $_REQUEST['positionId'];
    $lastWeekRoster = getLastWeekRoster($mysqli,$clientId,$stateId,$deptId,$positionId,$startDate);
    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);
    $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
    foreach($lastWeekRoster as $data) {
        foreach ($daterange as $date) {
            if($data['shiftDay'] == dayOfWeek($date->format('Y-m-d'))){
               // echo $date->format('Y-m-d').$data['shiftDay'].$clientId.$stateId.$deptId.$data['candidateId'].$data['shiftStart'].$data['shiftEnd'].'0'.''.''.''.''.$dateRange.$positionId.'OPEN'.'0'.$consultantId;
                echo saveAndDisplayShift($mysqli,$date->format('Y-m-d'),$data['shiftDay'],$clientId,$stateId,$deptId,$data['candidateId'],$data['shiftStart'],$data['shiftEnd'],$data['workBreak'],'','','','',$dateRange,$positionId,'OPEN',$data['addressId'],$consultantId,$_SESSION['userSession']);
            }
        }
    }
    //$shDay = dayOfWeek($startDate);
    /*foreach($lastWeekRoster as $data){
        if()
        $shiftStatus = saveAndDisplayShift($mysqli,$data['shiftDate'])
    }*/
    //echo var_dump($lastWeekRoster);

}else {
    $shiftStatus = saveAndDisplayShift($mysqli, $shDate, $shDay, $clid, $stid, $did, $canid, $StartTime, $EndTime, $workBreak, $note, $shiftCopy, $startDate, $endDate, $dateRange, $positionId, $shStatus, $addressId,$consultantId,$_SESSION['userSession']);
    /*$dataArray = json_decode($shiftStatus, true );
    foreach($dataArray as $item) {
        $insertId = $item['insertId'];
        saveShift($mysqli,$insertId);
    }*/
    echo $shiftStatus;
}
?>