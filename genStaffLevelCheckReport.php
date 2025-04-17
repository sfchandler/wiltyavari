<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");

$rosterStart = $_POST['rosterStart'];
$rosterEnd = $_POST['rosterEnd'];
$weekendingDate = $_POST['weekendingDate'];
$clientId = $_POST['clientId'];
$headerBackgroundArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFDAB9')));
$levelCheckData = getStaffLevelCheckReportData($mysqli,$clientId,$rosterStart,$rosterEnd);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employee ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Employee Name');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Client');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Shift Date');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'First Weekending Date Paid');

$objPHPExcel->getActiveSheet()->setTitle('STAFF LEVEL CHECK REPORT');
$rowCount=1;
foreach ($levelCheckData as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli,$data['candidateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getClientNameByClientId($mysqli,$data['clientId']));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftDate']);
    $paidDate = getFirstWorkedDuringPeriodByClient($mysqli,$data['candidateId'],$clientId,$weekendingDate);
    if(!empty($paidDate)) {
        $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($headerBackgroundArray);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount,$paidDate);

    }
}
$time = time();
$filePath = './reports/staffLevelCheckReport-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;