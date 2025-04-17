<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$clId = $_POST['clientId'];
$status = 'CONFIRMED';
if($clId == 'All'){
    $sql = $mysqli->prepare("SELECT 
                                      shift.candidateId,
                                      shift.shiftId,
                                      shift.shiftDate,
                                      shift.clientId,
                                      employee_covid_answers.answer,
                                      employee_covid_answers.answer_time,
                                      candidate.firstName,
                                      candidate.lastName,
                                      candidate.mobileNo,
                                      shift.shiftStart,
                                      shift.shiftEnd
                                    FROM
                                      shift
                                      LEFT OUTER JOIN employee_covid_answers ON (shift.shiftId = employee_covid_answers.shiftId)
                                      INNER JOIN candidate ON (shift.candidateId = candidate.candidateId)
                                    WHERE
                                      shift.shiftDate BETWEEN ? AND ? AND 
                                      shift.shiftStatus = ?
                                    ORDER BY
                                      shift.clientId") or die($mysqli->error);
                                     //'SICK','REJECTED','CANCELLED','CANCELLATION WITH NOTICE','CANCELLATION WITHOUT NOTICE','OPEN'
    $sql->bind_param("sss",  $startDate, $endDate,$status) or die($mysqli->error);
}else {
    $sql = $mysqli->prepare("SELECT 
                                      shift.candidateId,
                                      shift.shiftId,
                                      shift.shiftDate,
                                      shift.clientId,
                                      employee_covid_answers.answer,
                                      employee_covid_answers.answer_time,
                                      candidate.firstName,
                                      candidate.lastName,
                                      candidate.mobileNo,
                                      shift.shiftStart,
                                      shift.shiftEnd
                                    FROM
                                      shift
                                      LEFT OUTER JOIN employee_covid_answers ON (shift.shiftId = employee_covid_answers.shiftId)
                                      INNER JOIN candidate ON (shift.candidateId = candidate.candidateId)
                                    WHERE
                                      shift.clientId = ? AND
                                      shift.shiftDate BETWEEN ? AND ? AND 
                                      shift.shiftStatus = ?
                                    ORDER BY
                                      shift.clientId") or die($mysqli->error);
    $sql->bind_param("isss", $clId,$startDate, $endDate,$status) or die($mysqli->error);
}
$sql->execute();
$sql->bind_result($candidateId,
    $shiftId,
    $shiftDate,
    $clientId,
    $answer,
    $answer_time,
    $firstName,
    $lastName,
    $mobileNo,
    $shiftStart,
    $shiftEnd) or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if ($num_of_rows > 0) {
    while ($sql->fetch()) {
        $row = array('candidateId' => $candidateId, 'shiftId' => $shiftId,
            'shiftDate' => $shiftDate,'clientId'=>$clientId, 'answer' => $answer,
            'answer_time' => $answer_time, 'firstName' => $firstName, 'lastName' => $lastName, 'mobileNo' => $mobileNo,'shiftStart'=>$shiftStart,'shiftEnd'=>$shiftEnd);
        $dataArray[] = $row;
    }
} else {
    echo 'NODATA';//'No data '.$num_of_rows;
}
$dataSet = $dataArray;


$styleArray = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '666666'),
        'size' => 11,
        'name' => 'Calibri'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'f2f2f2')
    )
);
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CLIENT');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRSTNAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LASTNAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'POSITION');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'MOBILE');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SHIFTID');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'SHIFTDATE');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'CANDIDATEID');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'ANSWER');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'DATETIME');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'SHIFTSTART');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'SHIFTEND');
/*$objPHPExcel->getActiveSheet()->setCellValue('M1', 'SMSSENTTIME');*/

$objPHPExcel->getActiveSheet()->setTitle('COVID19 HEALTH REPORT');
$rowCount = 1;
foreach ($dataSet as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, getClientNameByClientId($mysqli,$data['clientId']));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli,getPositionIdByShiftId($mysqli,$data['shiftId'])));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['mobileNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftId']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['answer']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['answer_time']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['shiftStart']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['shiftEnd']);
    /*$objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getCOVIDDeclarationSMS($mysqli,$data['candidateId'],$data['shiftDate']));*/
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$time = time();
$clientName = '';
if(!empty($clId)){
    $clientName = $clId;
}else{
    $clientName = 'All_';
}
$fileName = './reports/' . $clientName . '_COVID19Report_' . $time . '.xlsx';
$objWriter->save($fileName);
echo $fileName;