<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$clientId = $_POST['clientId'];
$clientName = '';
if($clientId == 'All'){
    $clientName = 'All';
    $sql = $mysqli->prepare("SELECT
                                  timeclock.id,
                                  timeclock.shiftId,
                                  timeclock.candidateId,
                                  timeclock.shiftDate,
                                  timeclock.shiftDay,
                                  client.client,
                                  timeclock.positionId,
                                  timeclock.deptId,
                                  timeclock.jobCode,
                                  timeclock.checkIn,
                                  timeclock.checkOut,
                                  timeclock.workBreak,
                                  timeclock.wrkhrs,
                                  timeclock.supervicerId,
                                  timeclock.supervisorCheck,
                                  timeclock.supervisor,
                                  timeclock.approvalTime,
                                  timeclock.transport,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate_position.positionName,
                                  department.department
                                FROM
                                  timeclock
                                  INNER JOIN candidate ON (timeclock.candidateId = candidate.candidateId)
                                  INNER JOIN candidate_position ON (timeclock.positionId = candidate_position.positionid)
                                  INNER JOIN department ON (timeclock.deptId = department.deptId)
                                  INNER JOIN client ON (timeclock.clientId = client.clientId)
                                WHERE
                                  timeclock.shiftDate BETWEEN ? AND ?
                                ORDER BY
								  client.client, timeclock.candidateId ASC") or die($mysqli->error);
    $sql->bind_param("ss", $startDate, $endDate) or die($mysqli->error);
}else {
    $sql = $mysqli->prepare("SELECT
                                  timeclock.id,
                                  timeclock.shiftId,
                                  timeclock.candidateId,
                                  timeclock.shiftDate,
                                  timeclock.shiftDay,
                                  client.client,
                                  timeclock.positionId,
                                  timeclock.deptId,
                                  timeclock.jobCode,
                                  timeclock.checkIn,
                                  timeclock.checkOut,
                                  timeclock.workBreak,
                                  timeclock.wrkhrs,
                                  timeclock.supervicerId,
                                  timeclock.supervisorCheck,
                                  timeclock.supervisor,
                                  timeclock.approvalTime,
                                  timeclock.transport,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate_position.positionName,
                                  department.department
                                FROM
                                  timeclock
                                  INNER JOIN candidate ON (timeclock.candidateId = candidate.candidateId)
                                  INNER JOIN candidate_position ON (timeclock.positionId = candidate_position.positionid)
                                  INNER JOIN department ON (timeclock.deptId = department.deptId)
                                  INNER JOIN client ON (timeclock.clientId = client.clientId)
                                WHERE
                                  timeclock.clientId = ? AND
                                  timeclock.shiftDate BETWEEN ? AND ?
                                ORDER BY
								  client.client, timeclock.candidateId ASC") or die($mysqli->error);
    $sql->bind_param("iss", $clientId, $startDate, $endDate) or die($mysqli->error);
}
$sql->execute();
$sql->bind_result($id, $shiftId, $candidateId, $shiftDate, $shiftDay, $client, $positionId, $deptId, $jobCode, $checkIn, $checkOut, $workBreak, $wrkhrs, $supervicerId, $supervisorCheck, $supervisor, $approvalTime, $transport, $firstName, $lastName, $positionName, $department) or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if ($num_of_rows > 0) {
    while ($sql->fetch()) {
        $row = array('id' => $id, 'shiftId' => $shiftId, 'candidateId' => $candidateId, 'shiftDate' => $shiftDate, 'shiftDay' => $shiftDay, 'client' => $client, 'department' => $department, 'jobCode' => $jobCode, 'checkIn' => $checkIn, 'checkOut' => $checkOut, 'workBreak' => $workBreak, 'wrkhrs' => $wrkhrs, 'supervisorId' => $supervicerId, 'supervisorCheck' => $supervisorCheck, 'supervisor' => $supervisor, 'approvalTime' => $approvalTime, 'transport' => $transport, 'firstName' => $firstName, 'lastName' => $lastName, 'positionName' => $positionName);
        $dataArray[] = $row;
    }
} else {
    echo 'NODATA';//'No data '.$num_of_rows;
}
$dataSet = $dataArray;
$objPHPExcel = new PHPExcel();
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

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'CANDIDATE ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'SHIFT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SHIFT DAY');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CLIENT');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'POSITION');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'JOB CODE');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'CHECK IN');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'CHECK OUT');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'WORK BREAK');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'WORK HOURS');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'SUPERVISOR ID');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'SUPERVISOR CHECK');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'SUPERVISOR');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'APPROVAL TIME');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'TRANSPORT');

$objPHPExcel->getActiveSheet()->setTitle('TIME CLOCK REPORT');

$rowCount = 1;

foreach ($dataSet as $data) {
    $clientName = $data['client'];
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftDay']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['client']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['department']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['positionName']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['jobCode']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['checkIn']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['checkOut']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['workBreak']);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['wrkhrs']);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['supervisorId']);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['supervisorCheck']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['supervisor']);
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['approvalTime']);
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['transport']);
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$time = time();
$fileName = './reports/' . $clientName . '_timeclockReport_' . $time . '.xlsx';
$objWriter->save($fileName);
echo $fileName;