<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');

$shiftData = getReleasedConfirmedShiftReportData($mysqli);

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CLIENT');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'STATE');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SHIFT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'SHIFT DAY');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'SHIFT START');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'SHIFT END');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'WORK BREAK');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'WORK HOURS');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'SHIFT STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'CONSULTANT NAME');
$objPHPExcel->getActiveSheet()->setTitle('Released Shift Report');
$rowCount = 1;
foreach ($shiftData as $value){
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $value['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $value['candidateName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $value['client']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $value['state']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $value['department']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $value['shiftDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $value['shiftDay']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $value['shiftStart']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $value['shiftEnd']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $value['workBreak']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $value['wrkhrs']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, $value['shiftStatus']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, $value['consultantName']);
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('./reports/releasedConfirmedShiftsReport.xlsx');
echo './reports/releasedConfirmedShiftsReport.xlsx';