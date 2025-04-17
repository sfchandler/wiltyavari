<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
$weekendingDate = $_POST['weekendingDate'];
$candidateId = $_POST['candidateId'];
$headerBackgroundArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFDAB9')));
if(empty($candidateId)) {
    $transCodeData = getAttachedTransactionCodeInfoByWeekending($mysqli, $weekendingDate);
}else {
    $transCodeData = getAttachedTransactionCodeInfo($mysqli, $candidateId, $weekendingDate);
}
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employee ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Employee Name');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Transaction Code');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'JobCode');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Client');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Position');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Weekending Date');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Transaction Code Amount');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Transaction Code Type');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Transaction Code Tax Order');


$objPHPExcel->getActiveSheet()->setTitle('TRANSACTION CODE REPORT');
$rowCount=1;
foreach ($transCodeData as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli,$data['candidateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getTransCodeDescByTransCode($mysqli,$data['transCode']));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['jobCode']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli,$data['clientId']));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getCandidatePositionNameById($mysqli,$data['positionId']));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['weekendingDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['transCodeAmount']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, getTransCodeTypeNameByTransCode($mysqli,getTransCodeTypeByTransCode($mysqli,$data['transCode'])));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, getTaxOrderBasedOnTransactionCode($mysqli,$data['transCode']));
}
$time = time();
$filePath = './reports/transCodeReport-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;