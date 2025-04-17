<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");

$dataSet = getRegisteredCasuals($mysqli);

$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'MOBILE NO');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'CONSULTANT');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'REGISTERED DATE');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'EMPLOYEE STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'AUDIT STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'REGPACK STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'REGPACK SENT TIME');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'FOUND US BY');

$objPHPExcel->getActiveSheet()->setTitle('Roster Schedule');

$rowCount = 1;
foreach ($dataSet as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['mobileNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getConsultantName($mysqli,$data['consultantId']));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['created_at']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['empStatus']);

    if($data['auditStatus'] == '1'){
        $auditStatus = 'AUDITED';
    }else{
        $auditStatus = 'N/A';
    }
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $auditStatus);
    if($data['reg_pack_status'] == 1){
        $regpack = 'RECEIVED';
    }else{
        $regpack = '';
    }
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $regpack);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getRegPackSentTime($mysqli,$data['candidateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getCandidateFoundHow($mysqli,$data['candidateId']));
}
$time = time();
$filePath = './reports/registeredCasuals-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;