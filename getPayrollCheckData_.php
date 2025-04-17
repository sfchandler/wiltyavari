<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
error_reporting(E_ERROR | E_PARSE);
$clientid = $_REQUEST['clientid'];
$positionid = $_REQUEST['positionid'];
$deptid = $_REQUEST['deptid'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];
$action = $_REQUEST['action'];
$loggedInUserType = $_SESSION['userType'];
switch ($action) {
    case 'GET':
        if(isset($clientid)) {
            echo getPayrollCheckData($mysqli,$clientid,$positionid,$deptid,$startDate,$endDate,$candidateId,$loggedInUserType);
        }else{
            echo '<span>client not set</span>';
        }
        break;
    case 'EXCEL':
        try{
            if(isset($clientid)) {
                $payrollCheckData = genPayrollCheckExcel($mysqli,$clientid,$positionid,$deptid,$startDate,$endDate,$candidateId,$loggedInUserType);
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT ID');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DAY');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DATE');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'EMPLOYEE ID');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'NO PHONE INDICATOR');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CLIENT');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'POSITION');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'DEPARTMENT');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'JOBCODE');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'SHIFT START');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', 'SHIFT END');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', 'ROSTER WORK HOURS');
                $objPHPExcel->getActiveSheet()->setCellValue('N1', 'CHECK IN');
                $objPHPExcel->getActiveSheet()->setCellValue('O1', 'CHECK OUT');
                $objPHPExcel->getActiveSheet()->setCellValue('P1', 'CHECK IN WORK HOURS');
                $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'SUPERVISOR CHECK IN');
                $objPHPExcel->getActiveSheet()->setCellValue('R1', 'SUPERVISOR CHECK OUT');
                $objPHPExcel->getActiveSheet()->setCellValue('S1', 'CLOCK IN /SUPERVISOR BREAK');
                $objPHPExcel->getActiveSheet()->setCellValue('T1', 'SUPERVISOR WORK HOURS');
                $objPHPExcel->getActiveSheet()->setCellValue('U1', 'TIMESHEET WORK HOURS');
                $objPHPExcel->getActiveSheet()->setCellValue('V1', 'COMMENTS');
                $objPHPExcel->getActiveSheet()->setTitle('Payroll Check Export');
                $rowCount = 1;
                foreach ($payrollCheckData as $data) {
                    $rowCount++;

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDay']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDate']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['candidateId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['candidateName']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['noPhone']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['client']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['position']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['department']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['jobCode']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['shiftStart']);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['shiftEnd']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['rosterWorkHours']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['checkIn']);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['checkOut']);
                    $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['clockInWorkHours']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['supervisorCheckIn']);
                    $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['supervisorCheckOut']);
                    $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['supervisorBreak']);
                    $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $data['supervisorWorkHours']);
                    $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $data['timesheetWrk']);
                    $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, $data['timeclockComments']);
                }
                $time = time();
                $fullPath = './timesheet/payroll_check_excel-' . $time . '.xlsx';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($fullPath);
                echo $fullPath;
            }else{
                echo '<span>client not set</span>';
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
        break;
}


?>