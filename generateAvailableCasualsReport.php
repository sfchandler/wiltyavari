<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
ini_set('max_execution_time', '0');
$clientId = $_REQUEST['clientId'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$empStatus = 'ACTIVE';

/*$begin = new DateTime($startDate);
$end = new DateTime($endDate);

$interval = DateInterval::createFromDateString('0 day');
$period = new DatePeriod($begin, $interval, $end);
foreach ($period as $dt) {
    echo $dt->format("Y-m-d").'<br>';
}*/

function returnDates($fromdate, $todate) {
    $fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
    $todate = \DateTime::createFromFormat('Y-m-d', $todate);
    return new \DatePeriod(
        $fromdate,
        new \DateInterval('P1D'),
        $todate->modify('+1 day')
    );
}

$datePeriod = returnDates($startDate, $endDate);
/*foreach($datePeriod as $date) {
    echo $date->format('Y-m-d'), PHP_EOL;
}*/
$sql = $mysqli->prepare("SELECT 
                                    DISTINCT
                                      employee_allocation.candidateId
                                    FROM
                                      employee_allocation
                                    INNER JOIN candidate ON(employee_allocation.candidateId = candidate.candidateId)
                                    WHERE
                                      employee_allocation.clientId = ?
                                    AND
                                      candidate.empStatus = ?  
                                    ORDER BY  employee_allocation.candidateId") or die($mysqli->error);
$sql->bind_param("ss",$clientId,$empStatus) or die($mysqli->error);
$sql->execute();
$sql->bind_result($candidateId)or die($mysqli->error);
$sql->store_result();
$dataArray = array();
while($sql->fetch()){
    foreach ($datePeriod as $dt) {
        $shDate = $dt->format("Y-m-d");
        $shiftId = checkForShifts($mysqli, $clientId, $shDate, $candidateId);
        if($shiftId == null){
            //echo $shDate.' - '.$candidateId.' No shift '.displayShiftAvailabilityCalendarForReport($mysqli,$candidateId,$shDate).'<br>';
            $dataArray[] = array('shift_date'=>$shDate,'candidate_id'=>$candidateId);
            //echo $shDate.' '.$candidateId.'<br>';
        }/*else{
            echo $shDate.' - '.$candidateId.' Ex Shift '.getShiftInfoByShiftId($mysqli,$shiftId).'<br>';
        }*/
    }
}

function checkForShifts($mysqli,$clientId,$shiftDate,$canId){
    try {
        $sql = $mysqli->prepare("SELECT shiftId FROM shift WHERE shiftDate = ? AND clientId = ? AND candidateId = ? ORDER BY candidateId") or die($mysqli->error);
        $sql->bind_param("sis", $shiftDate, $clientId, $canId) or die($mysqli->error);
        $sql->execute();
        $shiftId =$sql->get_result()->fetch_object()->shiftId;
        return $shiftId;
    }catch (Exception $e){
        return $e->getMessage();
    }
}

try {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT DATE');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CANDIDATE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'CANDIDATE NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SHIFT AVAILABILITY');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'LAST PAY WEEKENDING');
    $objPHPExcel->getActiveSheet()->setTitle('Available Casuals Report');

    $rowCount = 1;
    foreach ($dataArray as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shift_date']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['candidate_id']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateFullName($mysqli,$data['candidate_id']));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, displayShiftAvailabilityCalendarForReport($mysqli,$data['candidate_id'],$data['shift_date']));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getLastPayWeekending($mysqli,$data['candidate_id']));
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filePath = './roster/availableCasualsReport.xlsx';
    $objWriter->save($filePath);
    echo $filePath;
}catch (Exception $e){
    echo $e->getMessage();
}
?>