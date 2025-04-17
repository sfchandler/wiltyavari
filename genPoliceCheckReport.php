<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
error_reporting(E_ALL);
ini_set('display_errors', true);
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$trCode = 1;
$dataSet = getPoliceCheckDeductedCasualsForPeriod($mysqli, $startDate, $endDate, $trCode);

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'WEEKENDING DATE');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'JOBCODE');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'TRANSACTION CODE');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'DEDUCTION AMOUNT');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'STATUS');

$objPHPExcel->getActiveSheet()->setTitle('Police Check Deduction Report');

$rowCount = 1;
foreach ($dataSet as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['weekendingDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['category']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['jobCode']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['transCode']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['deduction']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['status']);
}
$time = time();
$filePath = './reports/policeCheckDeductedCasuals-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;