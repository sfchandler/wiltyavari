<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/01/2019
 * Time: 1:41 PM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $dataArray = getAllCandidates($mysqli);
    var_dump($dataArray);
    /*$objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DAY');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DATE');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'EMPLOYEE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMPLOYEE NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'DEPARTMENT');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'JOBCODE');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SHIFT START');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'SHIFT END');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'WORK BREAK');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'WORK HOURS');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'WEEKENDING');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'TRANSPORT');
    $objPHPExcel->getActiveSheet()->setTitle('Employees Allocated Export');
    $rowCount = 1;
    foreach ($dataArray as $data) {
        $rowCount++;
        $fullName = getCandidateFullName($mysqli, $data['candidateId']) . '(' . getNickNameById($mysqli, $data['candidateId']) . ')';
        $clientName = getClientNameByClientId($mysqli, $data['clientId']);
        $empPosition = getPositionByPositionId($mysqli, $data['positionId']);
        $empDepartment = getDepartmentById($mysqli, getDepartmentIdByShiftId($mysqli, $data['shiftId']));

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDay']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $fullName);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $clientName);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $empPosition);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $empDepartment);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['jobCode']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['shiftStart']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['shiftEnd']);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['workBreak']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['wrkHrs']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['weekendingDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['transport']);
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $time = time();
    $fileName = './reports/'.$time.'-timesheetReport.xlsx';
    $objWriter->save($fileName);
    echo $fileName;*/
}catch (Exception $e){
    echo $e->getMessage();
}


