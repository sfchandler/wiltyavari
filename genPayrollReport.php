<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
$weekendingDate = $_POST['weekendingDate'];
$clientId = $_POST['clientId'];

$payrollData = getPayrollReportData($mysqli,$clientId,$weekendingDate);

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Level/Position');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Candidate Given Name');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Candidate Surname');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Candidate ID');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Weekending Date');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Transaction Type');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Pay Units');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Pay Rate');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Pay Amount');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Bill Units');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Bill Rate');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Bill Amount');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Job Description/Department');

$objPHPExcel->getActiveSheet()->setTitle('PAYROLL REPORT');
$rowCount=1;
foreach ($payrollData as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['positionName']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, date('d/m/Y',strtotime($data['weekendingDate'])));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['category']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['units']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['rate']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['amount']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['units']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['chargeRate']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['chargeAmount']);
    $deptData = getDepartmentsForPayroll($mysqli,$data['candidateId'],$data['clientId'],$data['positionId'],$data['weekendingDate']);
    $jobDescription = '';
    $comma = ',  ';
    $k = 0;
    $len = count($deptData);
    foreach($deptData as $deptRec){
        /*if ($k != $len - 1) {
            $jobDescription = $jobDescription.$deptRec['department'].$comma;
        }else{
            $jobDescription = $jobDescription.$deptRec['department'];
        }
        $k++;*/
        $jobDescription = $jobDescription.$deptRec['department'];
    }
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount,$jobDescription);
}
$time = time();
$filePath = './reports/payrollReport-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;