<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$startDate = $_POST['startWkDate'];
$endDate = $_POST['endWkDate'];
$clientId = $_POST['clientId'];
$client = getClientNameByClientId($mysqli,$clientId);
$supCheck = 'N';
$sql = $mysqli->prepare("SELECT 
                                  timeclock.id,
                                  timeclock.shiftId,
                                  timeclock.candidateId,
                                  timeclock.shiftDate,
                                  timeclock.shiftDay,
                                  timeclock.clientId,
                                  timeclock.positionId,
                                  timeclock.jobCode,
                                  timeclock.checkIn,
                                  timeclock.checkOut,
                                  timeclock.supervisorCheckIn,
                                  timeclock.supervisorCheckOut,
                                  timeclock.workBreak,
                                  timeclock.wrkhrs,
                                  timeclock.supervisor,
                                  timeclock.supervisorCheck,
                                  shift.shiftStart AS rosterStart,
                                  shift.shiftEnd AS rosterEnd,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate_position.positionName,
                                  department.department
                                FROM
                                  timeclock
                                INNER JOIN candidate ON (timeclock.candidateId = candidate.candidateId)
                                INNER JOIN candidate_position ON (timeclock.positionId = candidate_position.positionid)
                                INNER JOIN department ON (timeclock.deptId = department.deptId)
                                LEFT OUTER JOIN shift ON (timeclock.shiftId = shift.shiftId)  
                                WHERE
                                  timeclock.supervisorCheck = ?
                                AND timeclock.shiftDate BETWEEN ? AND ?
                                AND timeclock.clientId = ?     
                                ORDER BY timeclock.candidateId,timeclock.shiftDate") or die($mysqli->error);
$sql->bind_param("sssi", $supCheck, $startDate, $endDate,$clientId) or die($mysqli->error);
$sql->execute();
$sql->bind_result($id,
    $shiftId,
    $candidateId,
    $shiftDate,
    $shiftDay,
    $clientId,
    $positionId,
    $jobCode,
    $checkIn,
    $checkOut,
    $supervisorCheckIn,
    $supervisorCheckOut,
    $workBreak,
    $wrkhrs,
    $supervicerId,
    $supervisorCheck,
    $rosterStart,
    $rosterEnd,
    $firstName,
    $lastName,
    $positionName,
    $department) or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
$response = '';
if ($num_of_rows > 0) {
    while ($sql->fetch()) {
        $row = array('id' => $id,
            'shiftId' => $shiftId,
            'candidateId' => $candidateId,
            'shiftDate' => $shiftDate,
            'shiftDay' => $shiftDay,
            'client' => $client,
            'positionName' => $positionName,
            'department' => $department,
            'jobCode' => $jobCode,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'workBreak' => $workBreak,
            'wrkhrs' => $wrkhrs,
            'rosterStart'=> $rosterStart,
            'rosterEnd'=> $rosterEnd,
            'supervisor' => $supervisor,
            'supervisorCheck' => $supervisorCheck,
            'firstName' => $firstName,
            'lastName' => $lastName);
        $dataArray[] = $row;
    }
} else {
    $response = 'NODATA';
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
try {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);
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
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'ROSTER START');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'ROSTER END');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'SUPERVISOR CHECK');

    $objPHPExcel->getActiveSheet()->setTitle('PENDING SUPERVISOR APPROVAL');

    $rowCount = 1;
    foreach ($dataSet as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['firstName']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['lastName']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftDay']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $client);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['department']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['positionName']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['jobCode']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['checkIn']);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['checkOut']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['workBreak']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['wrkhrs']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['rosterStart']);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['rosterEnd']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['supervisorCheck']);
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $time = time();
    $fileName = './reports/' . $client . '_pendingSupervisorReport_' . $time . '.xlsx';
    $objWriter->save($fileName);
    $response = $fileName;
}catch (Exception $e){
    $response = $e->getMessage();
}
echo $response;